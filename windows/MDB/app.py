# app.py
# -------------------- IMPORTS -------------------- #
from flask import Flask, render_template, request, jsonify, session, redirect, url_for, flash
from connection import local_connection, remote_connection
from datetime import datetime
from functools import wraps
import webbrowser
from threading import Timer
import sys
import os
import threading
import traceback
from updater import get_update_info, perform_update, get_current_version

# -------------------- FLASK APP SETUP -------------------- #
def get_resource_path(rel_path):
    if getattr(sys, "frozen", False):
        base = getattr(sys, "_MEIPASS", os.path.dirname(os.path.abspath(__file__)))
        return os.path.join(base, rel_path)
    return os.path.join(os.path.dirname(os.path.abspath(__file__)), rel_path)

template_dir = get_resource_path("templates")
static_dir = get_resource_path("static")

app = Flask(__name__, static_folder=static_dir, template_folder=template_dir)
app.secret_key = "change-this-to-a-secure-random-key"

# -------------------- LICENSE CHECK FUNCTION -------------------- #
def check_license_expiry(branch_code):
    conn = None
    try:
        conn = remote_connection()
        if conn is None:
            return False, None

        cur = conn.cursor(dictionary=True)
        cur.execute(
            "SELECT status, expiry_date FROM licence_management WHERE branch_code=%s",
            (branch_code,)
        )
        row = cur.fetchone()
        if not row:
            return False, None
        status = row.get("status")
        expiry_date = row.get("expiry_date")
        if status == 0:
            return True, expiry_date
        if expiry_date and expiry_date < datetime.now().date():
            return True, expiry_date
        return False, expiry_date
    except Exception:
        return False, None
    finally:
        if conn:
            try: conn.close()
            except: pass

# -------------------- LOGIN DECORATOR -------------------- #
def require_login(f):
    @wraps(f)
    def decorated_function(*args, **kwargs):
        if "branch_code" not in session:
            return redirect(url_for("login"))

        branch_code = session.get("branch_code")
        is_expired, expiry_date = check_license_expiry(branch_code)
        if is_expired:
            session["license_expired_info"] = {
                "branch_code": branch_code,
                "branch_name": session.get("branch_name", "Unknown"),
                "expiry_date": expiry_date
            }
            return redirect(url_for("license_expired"))
        return f(*args, **kwargs)
    return decorated_function

# -------------------- LOGIN -------------------- #
@app.route("/login", methods=["GET", "POST"])
def login():
    if "branch_code" in session:
        return redirect(url_for("index"))

    if request.method == "GET":
        ok, msg, branch_code_prefill, branch_name_prefill = verify_local_remote_license()
        if not ok:
            flash(msg, "error")
            return render_template("login.html")
        return render_template("login.html",
            branch_code=branch_code_prefill,
            branch_name=branch_name_prefill)

    branch_code = request.form.get("branch_code", "").strip()
    password = request.form.get("password", "").strip()

    if not branch_code or not password:
        flash("Branch code and password are required.", "error")
        return render_template("login.html")

    conn = None
    try:
        conn = remote_connection()
        if conn is None:
            flash("Unable to connect to database.", "error")
            return render_template("login.html")

        cur = conn.cursor(dictionary=True)
        cur.execute(
            "SELECT id, branch_code, branch_name FROM branch WHERE branch_code=%s AND manager_password=%s",
            (branch_code, password)
        )
        row = cur.fetchone()
        if row:
            session["branch_id"] = row["id"]
            session["branch_code"] = row["branch_code"]
            session["branch_name"] = row["branch_name"]
            return redirect(url_for("index"))

        flash("Invalid branch code or password.", "error")
        return render_template("login.html")
    except Exception as e:
        flash(f"Error: {str(e)}", "error")
        return render_template("login.html")
    finally:
        if conn:
            try: conn.close()
            except: pass

@app.route("/logout")
def logout():
    session.clear()
    return redirect(url_for("login"))

# -------------------- LOCAL ODBC: BRANCH CODE FETCH (ROBUST) -------------------- #
def get_branch_codes():
    """
    Return (local_branch_code, remote_branch_code).
    Tries several likely tables/columns so missing branchmaster won't break app.
    """
    try:
        conn = local_connection()
        if conn is None:
            return None, None
        cur = conn.cursor()

        local_branch_code = None
        remote_branch_code = None

        # Try mobile_pigmy (common)
        try:
            cur.execute("SELECT TOP 1 branch_code FROM mobile_pigmy")
            row = cur.fetchone()
            if row and row[0]:
                local_branch_code = row[0]
                remote_branch_code = row[0]
        except Exception:
            pass

        # fallback: branchmaster.bracod
        if not local_branch_code:
            try:
                cur.execute("SELECT TOP 1 bracod FROM branchmaster")
                row = cur.fetchone()
                if row and row[0]:
                    local_branch_code = row[0]
            except Exception:
                pass

        # fallback: mobile_pigmy.bracod
        if not local_branch_code:
            try:
                cur.execute("SELECT TOP 1 bracod FROM mobile_pigmy")
                row = cur.fetchone()
                if row and row[0]:
                    local_branch_code = row[0]
            except Exception:
                pass

        # remote_branch_code fallback: mobile_pigmy.branch_code
        if not remote_branch_code:
            try:
                cur.execute("SELECT TOP 1 branch_code FROM mobile_pigmy")
                row = cur.fetchone()
                if row and row[0]:
                    remote_branch_code = row[0]
            except Exception:
                pass

        try: conn.close()
        except: pass
        return local_branch_code, remote_branch_code
    except Exception:
        return None, None

# ---------------- VERIFY LOCAL VS REMOTE LICENSE ---------------- #
def verify_local_remote_license():
    try:
        conn = local_connection()
        if conn is None:
            return False, "Unable to connect local DB.", None, None

        cur = conn.cursor()
        local_branch = None
        local_key = None

        # mobile_pigmy often has license info
        try:
            cur.execute("SELECT TOP 1 branch_code, licence_key FROM mobile_pigmy")
            row = cur.fetchone()
            if row:
                # row may contain (branch_code, licence_key) or only (licence_key)
                if len(row) >= 2:
                    local_branch = row[0]
                    local_key = row[1]
                else:
                    local_branch = row[0]
        except Exception:
            pass

        # fallback: mobile_pigmy mobile table fields
        if not local_key:
            try:
                cur.execute("SELECT TOP 1 licence_key FROM mobile_pigmy")
                row = cur.fetchone()
                if row and row[0]:
                    local_key = row[0]
            except Exception:
                pass

        try: conn.close()
        except: pass
    except Exception as e:
        return False, f"Local license error: {e}", None, None

    if not local_branch or not local_key:
        return False, "Local branch code or key empty.", local_branch, None

    # ---- Remote MySQL validation ---- #
    try:
        rconn = remote_connection()
        if rconn is None:
            return False, "Cannot connect remote DB.", local_branch, None

        rcur = rconn.cursor(dictionary=True)
        rcur.execute(
            "SELECT licence_key, status, expiry_date FROM licence_management WHERE branch_code=%s",
            (local_branch,)
        )
        r = rcur.fetchone()
        if not r:
            try: rconn.close()
            except: pass
            return False, "Remote license missing.", local_branch, None

        if r.get("licence_key") != local_key:
            try: rconn.close()
            except: pass
            return False, "License key mismatch.", local_branch, None

        if r.get("status") == 0:
            try: rconn.close()
            except: pass
            return False, "License inactive.", local_branch, None

        if r.get("expiry_date") and r.get("expiry_date") < datetime.now().date():
            try: rconn.close()
            except: pass
            return False, "License expired.", local_branch, None

        rcur.execute("SELECT branch_name FROM branch WHERE branch_code=%s", (local_branch,))
        b = rcur.fetchone()
        final_name = b["branch_name"] if b else None

        try: rconn.close()
        except: pass
        return True, "OK", local_branch, final_name
    except Exception as e:
        return False, str(e), local_branch, None

# -------------------- LICENSE EXPIRED PAGE -------------------- #
@app.route("/license-expired")
def license_expired():
    license_info = session.pop("license_expired_info", {})
    session.clear()
    return render_template(
        "license_expired.html",
        branch_code=license_info.get("branch_code", "Unknown"),
        branch_name=license_info.get("branch_name", "Unknown"),
        expiry_date=license_info.get("expiry_date", "Unknown")
    )

# -------------------- INDEX + SIMPLE PAGES -------------------- #
@app.route("/")
@require_login
def index():
    branch_name = None
    try:
        lconn = local_connection()
        local_branch = None
        local_branch_name = None
        if lconn:
            lcur = lconn.cursor()
            try:
                lcur.execute("SELECT TOP 1 branch_code, branch_name FROM mobile_pigmy")
                lrow = lcur.fetchone()
                if lrow:
                    local_branch = lrow[0]
                    local_branch_name = lrow[1] if len(lrow) > 1 else None
            except Exception:
                local_branch = None
                local_branch_name = None
            finally:
                try: lconn.close()
                except: pass

            if local_branch:
                rconn = remote_connection()
                if rconn:
                    try:
                        rcur = rconn.cursor(dictionary=True)
                        rcur.execute("SELECT branch_name FROM branch WHERE branch_code=%s LIMIT 1", (local_branch,))
                        rb = rcur.fetchone()
                        if rb and rb.get("branch_name"):
                            branch_name = rb.get("branch_name")
                        else:
                            branch_name = local_branch_name
                    except Exception:
                        branch_name = local_branch_name
                    finally:
                        try: rconn.close()
                        except: pass
    except Exception:
        branch_name = None
    
    # Get current version
    current_version = get_current_version()
    
    return render_template("main.html", branch_name=branch_name, app_version=current_version)

@app.route("/com-to-pig")
@require_login
def com_to_pig_page():
    # New simplified page renders the updated template
    branch_name = None
    try:
        lconn = local_connection()
        if lconn:
            lcur = lconn.cursor()
            try:
                # try several possible branch name columns
                try:
                    lcur.execute("SELECT TOP 1 branameng, bracod FROM branchmaster")
                    lrow = lcur.fetchone()
                    if lrow:
                        branch_name = lrow[0] if len(lrow) > 0 else None
                except Exception:
                    pass
                if not branch_name:
                    try:
                        lcur.execute("SELECT TOP 1 branch_name, branch_code FROM mobile_pigmy")
                        lrow = lcur.fetchone()
                        if lrow and len(lrow) >= 1:
                            branch_name = lrow[0]
                    except Exception:
                        pass
            except Exception:
                pass
            finally:
                try: lconn.close()
                except: pass
    except Exception:
        branch_name = None
    return render_template("com_to_pig.html", branch_name=branch_name)

@app.route("/pig-to-com")
@require_login
def pig_to_com():
    return render_template("pig_to_com.html")

# -------------------- UTIL: SAFE DATE -------------------- #
def safe_date_for_json(d):
    if not d:
        return None
    try:
        if hasattr(d, "strftime"):
            return d.strftime("%Y-%m-%d")
        return str(d)
    except Exception:
        return None

# -------------------- API: INITIAL DATA (ROBUST) -------------------- #
@app.route("/api/initial-data", methods=["GET"])
@require_login
def api_initial_data():
    try:
        conn = local_connection()
        if conn is None:
            return jsonify({"error": "Unable to connect to local DB."}), 500
        cur = conn.cursor()

        branch_name = None
        branch_code = None

        # Try mobile_pigmy first
        try:
            cur.execute("SELECT TOP 1 branch_name, branch_code FROM mobile_pigmy")
            row = cur.fetchone()
            if row:
                if len(row) >= 2:
                    branch_name, branch_code = row[0], row[1]
                else:
                    branch_code = row[0]
        except Exception:
            pass

        # Try branchmaster if present
        if not branch_name or not branch_code:
            try:
                cur.execute("SELECT TOP 1 branameng, bracod FROM branchmaster")
                row = cur.fetchone()
                if row:
                    branch_name = branch_name or (row[0] if len(row) > 0 else None)
                    branch_code = branch_code or (row[1] if len(row) > 1 else None)
            except Exception:
                pass

        # Fallback table 'branch'
        if not branch_name or not branch_code:
            try:
                cur.execute("SELECT TOP 1 branch_name, branch_code FROM branch")
                row = cur.fetchone()
                if row:
                    branch_name = branch_name or (row[0] if len(row) > 0 else None)
                    branch_code = branch_code or (row[1] if len(row) > 1 else None)
            except Exception:
                pass

        # Final fallback - branch_code only
        if not branch_code:
            try:
                cur.execute("SELECT TOP 1 branch_code FROM mobile_pigmy")
                row = cur.fetchone()
                if row and row[0]:
                    branch_code = row[0]
            except Exception:
                pass

        if not branch_code:
            try: conn.close()
            except: pass
            return jsonify({"error": "Branch information not found in local DB (tried mobile_pigmy, branchmaster, branch)."}), 404

        # Try to fetch master pigmy types
        types = []
        try:
            cur.execute("SELECT pigdepeng FROM masterpigmy WHERE bracod=?", (branch_code,))
            types = [r[0] for r in cur.fetchall() or []]
        except Exception:
            types = []

        try: conn.close()
        except: pass

        return jsonify({
            "branch": {"name": branch_name or "", "code": branch_code},
            "types": types
        })
    except Exception as e:
        return jsonify({"error": str(e)}), 500

# -------------------- API: AGENTS (OLD) -------------------- #
@app.route("/api/agents", methods=["GET"])
@require_login
def api_agents():
    deposit_type = request.args.get("type")
    branch_code = request.args.get("branch")
    if not deposit_type or not branch_code:
        return jsonify({"error": "type and branch params required"}), 400
    try:
        conn = local_connection()
        if conn is None:
            return jsonify({"error": "Unable to connect to local DB."}), 500
        cur = conn.cursor()
        cur.execute("""
            SELECT agenum, agenameng FROM createpigmyagent
            WHERE pigdep=? AND bracod=? ORDER BY agenum
        """, (deposit_type, branch_code))
        agents = [{"agent_number": r[0], "agent_name": r[1]} for r in cur.fetchall() or []]
        try: conn.close()
        except: pass
        return jsonify({"agents": agents})
    except Exception as e:
        return jsonify({"error": str(e)}), 500

# -------------------- API: LOAD ACCOUNTS (COM -> PIG) original (kept) -------------------- #
@app.route("/api/load-accounts", methods=["POST"])
@require_login
def api_load_accounts():
    data = request.json or {}
    branch_code = data.get("branch_code")
    deposit_type = data.get("deposit_type")
    agent_number = data.get("agent_number")
    if not (branch_code and deposit_type and agent_number):
        return jsonify({"error": "branch_code, deposit_type and agent_number required"}), 400

    def safe_date(d):
        if d is None:
            return None
        try:
            if hasattr(d, "strftime"):
                return d.strftime("%Y-%m-%d")
            return str(d)
        except:
            return None

    try:
        conn = local_connection()
        if conn is None:
            return jsonify({"error": "Unable to connect local DB."}), 500
        cur = conn.cursor()

        cur.execute("""
            SELECT accnum, macnam, depdat, matdat, clodat, cusid
            FROM depositpigmynew
            WHERE typofpig=? AND agenum=? AND bracod=? AND clodat='1986-01-01'
            ORDER BY accnum
        """, (deposit_type, agent_number, branch_code))

        accounts = cur.fetchall() or []
        results = []
        total_balance = 0.0

        for i, acc in enumerate(accounts, start=1):
            accnum = acc[0]
            macnam = acc[1]
            depdat = acc[2]
            matdat = acc[3]
            clodat = acc[4]
            cusid = acc[5]

            try:
                cur.execute("SELECT mobnum FROM customer WHERE cusid=? AND bracod=?", (cusid, branch_code))
                mobrow = cur.fetchone()
            except Exception:
                mobrow = None

            if mobrow:
                raw_mobile = str(mobrow[0]) if mobrow[0] is not None else ""
                digits_only = ''.join(ch for ch in raw_mobile if ch.isdigit())
                if len(digits_only) == 10:
                    mobno = digits_only
                else:
                    mobno = "0"
            else:
                mobno = "0"

            try:
                cur.execute("""
                    SELECT COALESCE(SUM(credit),0) - COALESCE(SUM(debit),0)
                    FROM pigmyaccountposting
                    WHERE accnum=? AND typofpig=? AND agenum=? AND bracod=?
                """, (accnum, deposit_type, agent_number, branch_code))
                bal_row = cur.fetchone()
                bal = bal_row[0] if bal_row and bal_row[0] is not None else 0.0
            except Exception:
                bal = 0.0

            results.append({
                "sno": i,
                "accnum": accnum,
                "macnam": macnam,
                "mobno": mobno,
                "depdat": safe_date(depdat),
                "matdat": safe_date(matdat),
                "clodat": safe_date(clodat),
                "balance": float(bal)
            })

            try:
                total_balance += float(bal)
            except:
                pass

        try: conn.close()
        except: pass

        return jsonify({"results": results, "total_balance": total_balance})
    except Exception as e:
        try: conn.close()
        except: pass
        return jsonify({"error": str(e)}), 500

# -------------------- API: UPLOAD ACCOUNTS (COM -> REMOTE) original (kept) -------------------- #
@app.route("/api/upload-accounts", methods=["POST"])
@require_login
def api_upload_accounts():
    payload = request.json or {}
    branch_code = payload.get("branch_code")
    agent_number = payload.get("agent_number")
    accounts = payload.get("accounts") or []
    force_upload = payload.get("force", False)

    if not (branch_code and agent_number):
        return jsonify({"error": "branch_code and agent_number required"}), 400

    def safe_date(value):
        if not value:
            return None
        return str(value)

    try:
        local_branch_code, remote_branch_code = get_branch_codes()
        if not remote_branch_code:
            return jsonify({"error": "Remote branch code not found in local mobile_pigmy"}), 500

        conn = remote_connection()
        if conn is None:
            return jsonify({"error": "Unable to connect remote DB."}), 500
        cur = conn.cursor(dictionary=True)

        cur.execute("""
            SELECT COUNT(*) AS total 
            FROM transactions
            WHERE branch_code=%s AND agent_code=%s
        """, (remote_branch_code, agent_number))
        txn_count = cur.fetchone()['total']

        cur.execute("""
            SELECT COUNT(*) AS total 
            FROM accounts
            WHERE branch_code=%s AND agent_code=%s
        """, (remote_branch_code, agent_number))
        acc_count = cur.fetchone()['total']

        if txn_count > 0 and acc_count > 0:
            conn.close()
            return jsonify({
                "success": False,
                "requires_action": True,
                "type": "BLOCK",
                "message": "Before uploading new accounts, please clear the old transactions and accounts."
            })

        if txn_count == 0 and acc_count > 0 and not force_upload:
            conn.close()
            return jsonify({
                "success": False,
                "requires_action": True,
                "type": "CONFIRM",
                "message": "Existing accounts found. Uploading will overwrite old accounts. Continue?"
            })

        if acc_count > 0:
            cur.execute("""
                DELETE FROM accounts
                WHERE branch_code=%s AND agent_code=%s
            """, (remote_branch_code, agent_number))
            conn.commit()

        if len(accounts) == 0:
            conn.close()
            return jsonify({"success": False, "message": "No accounts to upload."})

        inserted_count = 0
        for row in accounts:
            accnum = row.get("accnum")
            macnam = row.get("macnam")
            depdat = safe_date(row.get("depdat"))
            balance = float(row.get("balance", 0.0))
            mobno = row.get("mobno")
            account_mobile = mobno
            if not accnum or not macnam:
                continue
            cur.execute("""
                INSERT INTO accounts (
                    branch_code,
                    agent_code,
                    account_number,
                    account_name,
                    account_old_balance,
                    account_new_balance,
                    account_mobile,
                    account_opening_date
                )
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
            """, (
                remote_branch_code,
                agent_number,
                accnum,
                macnam,
                balance,
                balance,
                account_mobile,
                depdat
            ))
            inserted_count += 1

        conn.commit()
        conn.close()

        return jsonify({
            "success": True,
            "message": f"Uploaded {inserted_count} accounts successfully.",
            "remote_branch_code": remote_branch_code
        })
    except Exception as e:
        try: conn.close()
        except: pass
        return jsonify({"success": False, "message": str(e)}), 500

# -------------------- PIGMY -> COMPUTER: LOAD PENDING -------------------- #
SESSION_USER = "system"

@app.route("/api/pig-to-com/agents", methods=["GET"])
@require_login
def pig_to_com_agents():
    """Fetch agent list from remote MySQL."""
    try:
        local_branch_code, remote_branch_code = get_branch_codes()

        conn = remote_connection()
        if conn is None:
            return jsonify({"error": "Unable to connect remote DB"}), 500

        cur = conn.cursor(dictionary=True)

        cur.execute("""
            SELECT DISTINCT agent_code AS ageno
            FROM agent
            WHERE branch_code=%s
            ORDER BY agent_code
        """, (remote_branch_code,))

        rows = cur.fetchall()
        conn.close()

        agents = [{"ageno": r["ageno"]} for r in rows]

        return jsonify({"agents": agents})

    except Exception as e:
        return jsonify({"error": str(e)}), 500



@app.route("/api/pig-to-com/load", methods=["POST"])
@require_login
def pig_to_com_load():
    """Load pending transactions from remote."""
    data = request.json or {}
    agent = data.get("agent_number")

    if not agent:
        return jsonify({"error": "agent_number required"}), 400

    local_branch_code, remote_branch_code = get_branch_codes()

    try:
        conn = remote_connection()
        if conn is None:
            return jsonify({"error": "Unable to connect remote DB"}), 500

        cur = conn.cursor(dictionary=True)

        cur.execute("""
            SELECT id, account_number, amount, date
            FROM transactions
            WHERE agent_code=%s AND branch_code=%s AND status=0
            ORDER BY date, id
        """, (agent, remote_branch_code))

        rows = cur.fetchall()
        conn.close()

        results = []
        total = 0

        for r in rows:
            amt = float(r["amount"])
            total += amt

            results.append({
                "id": r["id"],
                "accnum": r["account_number"],
                "amount": amt,
                "date": str(r["date"])
            })

        return jsonify({
            "count": len(results),
            "total_amount": total,
            "transactions": results
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 500



@app.route("/api/pig-to-com/download", methods=["POST"])
@require_login
def pig_to_com_download():
    """
    Save transactions from remote MySQL → Local Access table pig_to_com
    Also backup to backuptransaction table (MySQL)
    And delete processed remote transactions + accounts
    """
    data = request.json or {}

    agent = data.get("agent_number")
    txns = data.get("transactions")

    if not agent or not txns:
        return jsonify({"error": "Missing fields"}), 400

    local_branch_code, remote_branch_code = get_branch_codes()

    try:
        # ----- Connect Local Access -----
        local = local_connection()
        if local is None:
            return jsonify({"error": "Unable to connect local DB"}), 500
        lcur = local.cursor()

        # ----- Connect Remote MySQL -----
        remote = remote_connection()
        if remote is None:
            return jsonify({"error": "Unable to connect remote DB"}), 500
        rcur = remote.cursor(dictionary=True)

        # Fetch agent name for backuptransaction
        rcur.execute("""
            SELECT agent_name FROM agent
            WHERE agent_code=%s AND branch_code=%s
        """, (agent, remote_branch_code))
        ar = rcur.fetchone()
        agent_name = ar["agent_name"] if ar else "Unknown"

        count = 0
        total_amount = 0

        # Clear existing local pig_to_com records before inserting
        try:
            lcur.execute("DELETE FROM pig_to_com")
            try:
                local.commit()
            except Exception:
                pass
        except Exception:
            # If deletion fails (e.g., table missing), continue and let inserts fail if necessary
            pass

        for t in txns:
            accnum = t["accnum"]
            amount = float(t["amount"])
            tradate = str(t["date"])[:10]
            txn_id = t["id"]

            # ------------------------------------------------------------
            # INSERT INTO LOCAL ACCESS TABLE pig_to_com
            # ageno, accnum, amount, date
            # ------------------------------------------------------------
            lcur.execute("""
                INSERT INTO pig_to_com ([ageno], [accnum], [amount], [date])
                VALUES (?, ?, ?, ?)
            """, (agent, accnum, amount, tradate))

            # ------------------------------------------------------------
            # FETCH FULL ROW FROM REMOTE transactions FOR BACKUP
            # ------------------------------------------------------------
            rcur.execute("""
                SELECT transaction_id, branch_code, agent_code,
                       account_number, account_name, date, time, amount
                FROM transactions WHERE id=%s
            """, (txn_id,))
            row = rcur.fetchone()

            if row:
                # --------------------------------------------------------
                # INSERT INTO backuptransaction (MySQL)
                # --------------------------------------------------------
                rcur.execute("""
                    INSERT INTO backuptransaction (
                        transaction_id, branch_code, branch_name,
                        agent_code, agent_name, account_number,
                        account_name, date, time, amount
                    ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
                """, (
                    row["transaction_id"],
                    row["branch_code"],
                    session.get("branch_name"),
                    row["agent_code"],
                    agent_name,
                    row["account_number"],
                    row["account_name"],
                    row["date"],
                    row["time"],
                    row["amount"]
                ))

            # ------------------------------------------------------------
            # MARK remote transaction as processed
            # ------------------------------------------------------------
            rcur.execute(
                "UPDATE transactions SET status=1 WHERE id=%s",
                (txn_id,)
            )

            count += 1
            total_amount += amount

        # ------------------------------------------------------------
        # DELETE processed remote transactions
        # ------------------------------------------------------------
        rcur.execute("""
            DELETE FROM transactions
            WHERE status=1 AND agent_code=%s AND branch_code=%s
        """, (agent, remote_branch_code))

        # ------------------------------------------------------------
        # DELETE remote accounts for this agent
        # ------------------------------------------------------------
        rcur.execute("""
            DELETE FROM accounts
            WHERE agent_code=%s AND branch_code=%s
        """, (agent, remote_branch_code))

        # Commit both databases
        local.commit()
        remote.commit()

        # Close DBs
        local.close()
        remote.close()

        return jsonify({
            "success": True,
            "count": count,
            "total_amount": total_amount
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 500

# -------------------- NEW: COM -> PIG simplified workflow (confirmed schema) -------------------- #
@app.route("/api/com-to-pig/agents", methods=["GET"])
@require_login
def api_com_to_pig_agents():
    try:
        conn = local_connection()
        if conn is None:
            return jsonify({"error": "Unable to connect to local DB."}), 500
        cur = conn.cursor()
        cur.execute("SELECT DISTINCT ageno FROM com_to_pig ORDER BY ageno")
        rows = cur.fetchall() or []
        try: conn.close()
        except: pass
        agents = [{"agent_number": str(r[0])} for r in rows]
        return jsonify({"agents": agents})
    except Exception as e:
        try: conn.close()
        except: pass
        return jsonify({"error": str(e)}), 500

@app.route("/api/com-to-pig/load", methods=["POST"])
@require_login
def api_com_to_pig_load():
    data = request.json or {}
    agent_number = data.get("agent_number")
    if not agent_number:
        return jsonify({"error": "agent_number required"}), 400

    try:
        conn = local_connection()
        if conn is None:
            return jsonify({"error": "Unable to connect to local DB."}), 500
        cur = conn.cursor()

        # Use explicit correct columns confirmed by you:
        # ageno, accnum, macnam, mobno, balance, depdat
        cur.execute("""
            SELECT accnum, macnam, mobno, balance, depdat
            FROM com_to_pig
            WHERE ageno=?
            ORDER BY accnum
        """, (agent_number,))

        rows = cur.fetchall() or []
        try: conn.close()
        except: pass

        results = []
        total_balance = 0.0

        def safe_date_val(d):
            if not d:
                return None
            try:
                if hasattr(d, "strftime"):
                    return d.strftime("%Y-%m-%d")
                return str(d)
            except:
                return str(d)

        for i, r in enumerate(rows, start=1):
            # r is a tuple (accnum, macnam, mobno, balance, depdat)
            accnum = r[0] if len(r) > 0 else ""
            macnam = r[1] if len(r) > 1 else ""
            mobno = r[2] if len(r) > 2 else ""
            balance = r[3] if len(r) > 3 else 0.0
            depdat = r[4] if len(r) > 4 else None

            raw_mobile = str(mobno or "")
            digits = ''.join(ch for ch in raw_mobile if ch.isdigit())
            mobclean = digits if len(digits) == 10 else "0"

            try:
                balf = float(balance or 0.0)
            except:
                balf = 0.0

            results.append({
                "sno": i,
                "accnum": str(accnum),
                "macnam": macnam,
                "mobno": mobclean,
                "balance": balf,
                "depdat": safe_date_val(depdat)
            })
            total_balance += balf

        return jsonify({"results": results, "total_balance": total_balance})
    except Exception as e:
        try: conn.close()
        except: pass
        return jsonify({"error": str(e)}), 500

@app.route("/api/com-to-pig/upload", methods=["POST"])
@require_login
def api_com_to_pig_upload():
    payload = request.json or {}
    agent_number = payload.get("agent_number")
    accounts = payload.get("accounts") or []
    force = payload.get("force", False)

    if not agent_number:
        return jsonify({"success": False, "message": "agent_number required"}), 400
    if not isinstance(accounts, list) or len(accounts) == 0:
        return jsonify({"success": False, "message": "No accounts to upload."}), 400

    try:
        local_branch_code, remote_branch_code = get_branch_codes()
        if not remote_branch_code:
            return jsonify({"success": False, "message": "Remote branch code not found in local mobile_pigmy"}), 500

        rconn = remote_connection()
        if rconn is None:
            return jsonify({"success": False, "message": "Unable to connect remote DB."}), 500
        rcur = rconn.cursor(dictionary=True)

        # Safety checks
        rcur.execute("""
            SELECT COUNT(*) AS total FROM transactions
            WHERE branch_code=%s AND agent_code=%s
        """, (remote_branch_code, agent_number))
        txn_count = rcur.fetchone().get('total', 0)

        rcur.execute("""
            SELECT COUNT(*) AS total FROM accounts
            WHERE branch_code=%s AND agent_code=%s
        """, (remote_branch_code, agent_number))
        acc_count = rcur.fetchone().get('total', 0)

        if txn_count > 0 and acc_count > 0:
            try: rconn.close()
            except: pass
            return jsonify({
                "success": False,
                "requires_action": True,
                "type": "BLOCK",
                "message": "Before uploading new accounts, please clear old transactions and accounts on server."
            })

        if txn_count == 0 and acc_count > 0 and not force:
            try: rconn.close()
            except: pass
            return jsonify({
                "success": False,
                "requires_action": True,
                "type": "CONFIRM",
                "message": "Accounts already exist on server for this agent. Uploading will overwrite them. Continue?"
            })

        # Delete existing accounts if present
        if acc_count > 0:
            rcur.execute("""
                DELETE FROM accounts
                WHERE branch_code=%s AND agent_code=%s
            """, (remote_branch_code, agent_number))
            rconn.commit()

        inserted = 0
        for a in accounts:
            accnum = a.get("accnum")
            macnam = a.get("macnam")
            balance = a.get("balance", 0.0)
            mobno = a.get("mobno")
            depdat = a.get("depdat")

            if not accnum or not macnam:
                continue

            rcur.execute("""
                INSERT INTO accounts (
                    branch_code, agent_code, account_number, account_name,
                    account_old_balance, account_new_balance, account_mobile,
                    account_opening_date
                ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
            """, (
                remote_branch_code,
                agent_number,
                accnum,
                macnam,
                balance,
                balance,
                mobno,
                depdat
            ))
            inserted += 1

        rconn.commit()
        try: rconn.close()
        except: pass

        return jsonify({
            "success": True,
            "message": f"Uploaded {inserted} accounts successfully.",
            "inserted": inserted,
            "remote_branch_code": remote_branch_code
        })
    except Exception as e:
        try: rconn.close()
        except: pass
        return jsonify({"success": False, "message": str(e)}), 500

@app.route("/debug/com")
def debug_com():
    try:
        conn = local_connection()
        cur = conn.cursor()

        cur.execute("SELECT TOP 1 * FROM com_to_pig")
        row = cur.fetchone()

        # Column names
        cols = [desc[0] for desc in cur.description]

        # Convert row to list safely
        row_data = list(row) if row else None

        return jsonify({
            "columns": cols,
            "sample_row": row_data
        })

    except Exception as e:
        return jsonify({"error": str(e)})

@app.route("/debug/pig")
def debug_pig():
    try:
        conn = local_connection()
        cur = conn.cursor()
        cur.execute("SELECT TOP 1 * FROM pig_to_com")
        row = cur.fetchone()
        cols = [c[0] for c in cur.description]
        return {"columns": cols, "row": list(row) if row else None}
    except Exception as e:
        return {"error": str(e)}


# ========================= UPDATE API ========================= #

@app.route("/api/update/check", methods=["GET"])
@require_login
def api_check_update():
    """Check for available updates."""
    try:
        update_info = get_update_info()
        return jsonify(update_info)
    except Exception as e:
        return jsonify({"error": str(e)}), 500


@app.route("/api/update/install", methods=["POST"])
@require_login
def api_install_update():
    """Trigger update installation."""
    try:
        if not getattr(sys, 'frozen', False):
            return jsonify({"error": "Update only available for compiled executable"}), 400
        
        # Perform update in background thread
        def _update():
            perform_update()
        
        thread = threading.Thread(target=_update, daemon=True)
        thread.start()
        
        return jsonify({
            "success": True,
            "message": "Update started. Application will restart shortly."
        })
    except Exception as e:
        return jsonify({"error": str(e)}), 500


# -------------------- AUTO-OPEN BROWSER -------------------- #
def open_browser():
    webbrowser.open("http://127.0.0.1:5000/login")

# -------------------- RUN SERVER -------------------- #
if __name__ == "__main__":
    is_frozen = getattr(sys, "frozen", False)

    if is_frozen:
        # Setup error logging for frozen exe
        log_file = os.path.join(os.path.expanduser("~"), "SookthMobilePigmy_debug.log")
        
        try:
            with open(log_file, "w") as f:
                f.write("=== Sookth Mobile Pigmy Banking - Startup Log ===\n")
                f.write(f"Time: {datetime.now()}\n")
                f.write(f"Python: {sys.version}\n")
                f.write(f"Executable: {sys.executable}\n\n")
            
            try:
                from tray_manager import TrayManager
            except Exception as e:
                with open(log_file, "a") as f:
                    f.write(f"TrayManager import failed: {e}\n")
                TrayManager = None

            from werkzeug.serving import make_server
            
            with open(log_file, "a") as f:
                f.write("Imports successful\n")
            
            wsgi_server = make_server("127.0.0.1", 5000, app)
            
            with open(log_file, "a") as f:
                f.write("Flask server created\n")
            
            flask_thread = threading.Thread(target=wsgi_server.serve_forever, daemon=True)
            flask_thread.start()
            
            with open(log_file, "a") as f:
                f.write("Flask thread started\n")
            
            Timer(0.5, lambda: None).start()

            if TrayManager:
                with open(log_file, "a") as f:
                    f.write("Starting tray manager\n")
                
                tray = TrayManager(app, flask_thread)
                tray.run()
            else:
                with open(log_file, "a") as f:
                    f.write("Running without tray (fallback mode)\n")
                try:
                    wsgi_server.serve_forever()
                except KeyboardInterrupt:
                    wsgi_server.shutdown()
                    
        except Exception as e:
            with open(log_file, "a") as f:
                f.write(f"\n=== ERROR ===\n")
                f.write(f"{str(e)}\n")
                f.write(traceback.format_exc())
            # Show error in message box
            import ctypes
            ctypes.windll.user32.MessageBoxW(0, f"Error: {str(e)}\n\nCheck log: {log_file}", "Startup Error", 0x10)
            sys.exit(1)
    else:
        Timer(1, open_browser).start()
        app.run(debug=False, host="127.0.0.1", port=5000)
