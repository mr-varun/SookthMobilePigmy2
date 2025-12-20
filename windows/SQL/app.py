# app.py
from flask import Flask, render_template, request, jsonify, session, redirect, url_for, flash
from connection import local_connection, remote_connection
from datetime import datetime
from functools import wraps
import webbrowser
from threading import Timer
import sys
import os
import threading
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
    """Check if branch license is expired. Returns (is_expired, expiry_date)."""
    conn = None
    try:
        conn = remote_connection()
        if conn is None:
            return False, None  # If DB unavailable, allow access
        
        cur = conn.cursor(dictionary=True)
        cur.execute(
            "SELECT status, expiry_date FROM licence_management WHERE branch_code=%s",
            (branch_code,)
        )
        row = cur.fetchone()
        
        if not row:
            return False, None  # No license record
        
        status = row["status"]
        expiry_date = row["expiry_date"]
        
        # Check if expired
        if status == 0:  # Inactive
            return True, expiry_date
        
        if expiry_date and expiry_date < datetime.now().date():
            return True, expiry_date
        
        return False, expiry_date
    
    except Exception as e:
        print(f"License check error: {e}")
        return False, None
    
    finally:
        if conn:
            try:
                conn.close()
            except:
                pass


# -------------------- LOGIN DECORATOR -------------------- #
def require_login(f):
    @wraps(f)
    def decorated_function(*args, **kwargs):
        if "branch_code" not in session:
            return redirect(url_for("login"))
        
        # Check license on every request
        branch_code = session.get("branch_code")
        is_expired, expiry_date = check_license_expiry(branch_code)
        
        if is_expired:
            session_data = {
                "branch_code": branch_code,
                "branch_name": session.get("branch_name", "Unknown"),
                "expiry_date": expiry_date
            }
            # Store in session for the license_expired page to display
            session["license_expired_info"] = session_data
            return redirect(url_for("license_expired"))
        
        return f(*args, **kwargs)
    return decorated_function


# -------------------- LOGIN ROUTES -------------------- #
@app.route("/login", methods=["GET", "POST"])
def login():
    """Login page and authentication endpoint."""
    # If already logged in, redirect to home
    if "branch_code" in session:
        return redirect(url_for("index"))
    
    if request.method == "GET":
        # On page load verify local vs remote licence information
        ok, msg, branch_code_prefill, branch_name_prefill = verify_local_remote_license()
        if not ok:
            flash(msg, "error")
            return render_template("login.html")

        # Prefill branch_code and branch_name when rendering login
        return render_template("login.html", branch_code=branch_code_prefill, branch_name=branch_name_prefill)
    
    # POST: Handle login form submission
    branch_code = request.form.get("branch_code", "").strip()
    password = request.form.get("password", "").strip()
    
    if not branch_code or not password:
        flash("Branch code and password are required.", "error")
        return render_template("login.html")
    
    conn = None
    try:
        conn = remote_connection()
        if conn is None:
            flash("Unable to connect to database. Please try again.", "error")
            return render_template("login.html")
        
        cur = conn.cursor(dictionary=True)
        cur.execute(
            "SELECT id, branch_code, branch_name FROM branch WHERE branch_code=%s AND manager_password=%s",
            (branch_code, password)
        )
        row = cur.fetchone()
        
        if row:
            # Credentials match: set session
            session["branch_id"] = row["id"]
            session["branch_code"] = row["branch_code"]
            session["branch_name"] = row["branch_name"]
            return redirect(url_for("index"))
        else:
            flash("Invalid branch code or password.", "error")
            return render_template("login.html")
    
    except Exception as e:
        flash(f"Error: {str(e)}", "error")
        return render_template("login.html")
    
    finally:
        if conn:
            try:
                conn.close()
            except:
                pass


@app.route("/logout")
def logout():
    """Clear session and redirect to login."""
    session.clear()
    return redirect(url_for("login"))


# -------------------- UTIL: FETCH LOCAL/REMOTE BRANCH CODES -------------------- #

def get_branch_codes():
    """Fetch local branch code and remote branch code from local DB."""
    try:
        conn = local_connection()
        if conn is None:
            return None, None
        cur = conn.cursor()

        # Local branch code
        cur.execute("SELECT bracod FROM branchmaster LIMIT 1")
        row = cur.fetchone()
        local_branch_code = row[0] if row else None

        # Remote branch code stored locally
        try:
            cur.execute("SELECT branch_code FROM mobile_pigmy LIMIT 1")
            row2 = cur.fetchone()
            remote_branch_code = row2[0] if row2 else None
        except:
            remote_branch_code = None

        conn.close()
        return local_branch_code, remote_branch_code
    except:
        return None, None


def verify_local_remote_license():
    """Verify that local `mobile_pigmy` table contains `branch_code` and `licence_key`
    and that these match a row in remote `licence_management`.

    Returns: (ok: bool, message: str, branch_code: str|None, branch_name: str|None)
    """
    # Read from local DB
    lconn = None
    try:
        lconn = local_connection()
        if lconn is None:
            return False, "Unable to connect to local database.", None, None

        lcur = lconn.cursor(dictionary=True)
        # Try select columns; tolerate different column names for license
        try:
            lcur.execute("SELECT branch_code, licence_key FROM mobile_pigmy LIMIT 1")
        except Exception:
            try:
                lcur.execute("SELECT branch_code, license_key AS licence_key, branch_name FROM mobile_pigmy LIMIT 1")
            except Exception:
                return False, "Local `mobile_pigmy` table is missing expected columns.", None, None

        local_row = lcur.fetchone()
        if not local_row:
            return False, "Local licensing info missing in `mobile_pigmy` table.", None, None

        local_branch = (local_row.get("branch_code") or "").strip()
        local_key = (local_row.get("licence_key") or "").strip()
        local_branch_name = local_row.get("branch_name") or None

    except Exception as e:
        return False, f"Error reading local license: {e}", None, None
    finally:
        if lconn:
            try:
                lconn.close()
            except:
                pass

    if not local_branch or not local_key:
        return False, "Local branch code or licence key is empty.", local_branch or None, local_branch_name

    # Read remote licence_management
    rconn = None
    try:
        rconn = remote_connection()
        if rconn is None:
            return False, "Unable to connect to remote database.", local_branch, local_branch_name

        rcur = rconn.cursor(dictionary=True)
        rcur.execute(
            "SELECT licence_key, status, expiry_date FROM licence_management WHERE branch_code=%s LIMIT 1",
            (local_branch,)
        )
        remote_row = rcur.fetchone()
        if not remote_row:
            return False, "No remote licence record found for this branch.", local_branch, local_branch_name

        remote_key = (remote_row.get("licence_key") or "").strip()
        status = remote_row.get("status")
        expiry_date = remote_row.get("expiry_date")

        if remote_key != local_key:
            return False, "Licence key mismatch between local and remote records.", local_branch, local_branch_name

        # If licence is inactive (status == 0) or expired, block
        try:
            if status == 0:
                return False, "Licence is marked inactive on the server.", local_branch, local_branch_name
            if expiry_date and expiry_date < datetime.now().date():
                return False, "Licence is expired on the server.", local_branch, local_branch_name
        except Exception:
            pass

        # Try to fetch branch name from remote `branch` table using the branch_code
        branch_name_remote = None
        try:
            rcur.execute("SELECT branch_name FROM branch WHERE branch_code=%s LIMIT 1", (local_branch,))
            brow = rcur.fetchone()
            if brow and brow.get("branch_name"):
                branch_name_remote = brow.get("branch_name")
        except Exception:
            branch_name_remote = None

        # Prefer remote branch name when available
        final_branch_name = branch_name_remote or local_branch_name

        return True, "OK", local_branch, final_branch_name

    except Exception as e:
        return False, f"Error reading remote licence: {e}", local_branch, local_branch_name
    finally:
        if rconn:
            try:
                rconn.close()
            except:
                pass


# -------------------- MAIN ROUTES -------------------- #



@app.route("/license-expired")
def license_expired():
    """Display license expired page and clear session."""
    license_info = session.pop("license_expired_info", {})
    session.clear()
    
    return render_template(
        "license_expired.html",
        branch_code=license_info.get("branch_code", "Unknown"),
        branch_name=license_info.get("branch_name", "Unknown"),
        expiry_date=license_info.get("expiry_date", "Unknown")
    )


@app.route("/")
@require_login
def index():
    # Fetch branch name using local `mobile_pigmy.branch_code` and remote `branch.branch_name`
    branch_name = None
    try:
        # Attempt to read local mobile_pigmy for branch_code
        lconn = local_connection()
        if lconn:
            lcur = lconn.cursor(dictionary=True)
            try:
                lcur.execute("SELECT branch_code, branch_name FROM mobile_pigmy LIMIT 1")
                lrow = lcur.fetchone()
                local_branch = (lrow.get("branch_code") if lrow else None) if lrow is not None else None
                local_branch_name = (lrow.get("branch_name") if lrow else None) if lrow is not None else None
            except Exception:
                # Try alternative column name for license key variant (no branch_name)
                try:
                    lcur.execute("SELECT branch_code FROM mobile_pigmy LIMIT 1")
                    lrow2 = lcur.fetchone()
                    # when using non-dictionary cursor, index 0 contains branch_code
                    if lrow2:
                        # support both tuple and dict-like depending on cursor type
                        if isinstance(lrow2, dict):
                            local_branch = lrow2.get("branch_code")
                        else:
                            local_branch = lrow2[0]
                        local_branch_name = None
                    else:
                        local_branch = None
                        local_branch_name = None
                except Exception:
                    local_branch = None
                    local_branch_name = None
            finally:
                try:
                    lconn.close()
                except:
                    pass

            # Query remote branch table for branch_name
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
                        try:
                            rconn.close()
                        except:
                            pass
    except Exception:
        branch_name = None

    # Get current version
    current_version = get_current_version()
    
    return render_template("main.html", branch_name=branch_name, app_version=current_version)


@app.route("/com-to-pig")
@require_login
def com_to_pig():
    return render_template("com_to_pig.html")


@app.route("/pig-to-com")
@require_login
def pig_to_com():
    return render_template("pig_to_com.html")


# -------------------- INITIAL DATA -------------------- #

@app.route("/api/initial-data", methods=["GET"])
@require_login
def api_initial_data():
    try:
        conn = local_connection()
        cur = conn.cursor()

        cur.execute("SELECT branameng, bracod FROM branchmaster LIMIT 1")
        row = cur.fetchone()
        if not row:
            conn.close()
            return jsonify({"error": "No branch found."}), 404

        branch_name, branch_code = row[0], row[1]

        cur.execute("SELECT pigdepeng FROM masterpigmy WHERE bracod=%s", (branch_code,))
        types = [r[0] for r in cur.fetchall() or []]

        conn.close()

        return jsonify({
            "branch": {"name": branch_name, "code": branch_code},
            "types": types
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 500


# -------------------- AGENT LIST API -------------------- #

@app.route("/api/agents", methods=["GET"])
@require_login
def api_agents():
    deposit_type = request.args.get("type")
    branch_code = request.args.get("branch")
    if not deposit_type or not branch_code:
        return jsonify({"error": "type and branch params required"}), 400

    try:
        conn = local_connection()
        cur = conn.cursor()

        cur.execute("""
            SELECT agenum, agenameng FROM createpigmyagent
            WHERE pigdep=%s AND bracod=%s ORDER BY agenum
        """, (deposit_type, branch_code))

        agents = [{"agent_number": r[0], "agent_name": r[1]} for r in cur.fetchall() or []]

        conn.close()
        return jsonify({"agents": agents})

    except Exception as e:
        return jsonify({"error": str(e)}), 500


# -------------------- COMPUTER → PIGMY: LOAD ACCOUNTS -------------------- #

@app.route("/api/load-accounts", methods=["POST"])
@require_login
def api_load_accounts():
    data = request.json or {}

    branch_code = data.get("branch_code")
    deposit_type = data.get("deposit_type")
    agent_number = data.get("agent_number")

    if not (branch_code and deposit_type and agent_number):
        return jsonify({"error": "branch_code, deposit_type and agent_number required"}), 400

    # ---- SAFE DATE FUNCTION ---- #
    def safe_date(d):
        if d is None:
            return None
        try:
            return d.strftime("%Y-%m-%d")
        except:
            return None

    try:
        conn = local_connection()
        cur = conn.cursor()

        cur.execute("""
            SELECT accnum, macnam, depdat, matdat, clodat, cusid
            FROM depositpigmynew
            WHERE typofpig=%s AND agenum=%s AND bracod=%s AND clodat='1986-01-01'
            ORDER BY accnum
        """, (deposit_type, agent_number, branch_code))

        accounts = cur.fetchall() or []
        results = []
        total_balance = 0.0

        for i, acc in enumerate(accounts, start=1):
            accnum, macnam, depdat, matdat, clodat, cusid = acc


            #fetch bobile no from customermaster table
            cur.execute("""
                SELECT mobnum FROM customer
                WHERE cusid=%s AND bracod=%s
            """, (cusid, branch_code))

            mobrow = cur.fetchone()

            if mobrow:
                raw_mobile = str(mobrow[0])
                # Keep only digits
                digits_only = ''.join(ch for ch in raw_mobile if ch.isdigit())

                # Check if exactly 10 digits
                if len(digits_only) == 10:
                    mobno = digits_only
                else:
                    mobno = "0"
            else:
                mobno = "0"


            # Load balance
            cur.execute("""
                SELECT COALESCE(SUM(credit),0) - COALESCE(SUM(debit),0)
                FROM pigmyaccountposting
                WHERE accnum=%s AND typofpig=%s AND agenum=%s AND bracod=%s
                      AND tradat <= CURDATE()
            """, (accnum, deposit_type, agent_number, branch_code))

            bal = cur.fetchone()[0] or 0.0

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

            total_balance += float(bal)

        conn.close()

        return jsonify({"results": results, "total_balance": total_balance})

    except Exception as e:
        try:
            conn.close()
        except:
            pass
        return jsonify({"error": str(e)}), 500

# -------------------- COMPUTER → PIGMY: UPLOAD ACCOUNTS -------------------- #

@app.route("/api/upload-accounts", methods=["POST"])
@require_login
def api_upload_accounts():
    payload = request.json or {}

    branch_code = payload.get("branch_code")
    agent_number = payload.get("agent_number")
    accounts = payload.get("accounts") or []
    force_upload = payload.get("force", False)  # <--- Confirmation flag

    if not (branch_code and agent_number):
        return jsonify({"error": "branch_code and agent_number required"}), 400

    # ---- Helper for date ----
    def safe_date(value):
        if not value:
            return None
        return str(value)

    try:
        # Get local + remote branch mapping
        local_branch_code, remote_branch_code = get_branch_codes()

        if not remote_branch_code:
            return jsonify({"error": "Remote branch code not found in mobile_pigmy table"}), 500

        conn = remote_connection()
        cur = conn.cursor(dictionary=True)

        # ====================== CHECK EXISTING DATA ====================== #

        # Check pending or existing transactions
        cur.execute("""
            SELECT COUNT(*) AS total 
            FROM transactions
            WHERE branch_code=%s AND agent_code=%s
        """, (remote_branch_code, agent_number))
        txn_count = cur.fetchone()['total']

        # Check already stored accounts
        cur.execute("""
            SELECT COUNT(*) AS total 
            FROM accounts
            WHERE branch_code=%s AND agent_code=%s
        """, (remote_branch_code, agent_number))
        acc_count = cur.fetchone()['total']

        # ---------------- CONDITION RULES ---------------- #

        # Case 1: BLOCK upload if both accounts + transactions exist
        if txn_count > 0 and acc_count > 0:
            conn.close()
            return jsonify({
                "success": False,
                "requires_action": True,
                "type": "BLOCK",
                "message": "Before uploading new accounts, please clear the old transactions and accounts."
            })

        # Case 2: Only accounts exist → ask confirmation
        if txn_count == 0 and acc_count > 0 and not force_upload:
            conn.close()
            return jsonify({
                "success": False,
                "requires_action": True,
                "type": "CONFIRM",
                "message": "Existing accounts found. Uploading will overwrite old accounts. Continue?"
            })

        # If confirmed OR no accounts exist → proceed
        # Delete old accounts if needed
        if acc_count > 0:
            cur.execute("""
                DELETE FROM accounts
                WHERE branch_code=%s AND agent_code=%s
            """, (remote_branch_code, agent_number))
            conn.commit()

        # ====================== INSERT NEW ACCOUNTS ====================== #

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
                continue  # ignore incomplete rows

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
        try:
            conn.close()
        except:
            pass
        return jsonify({"success": False, "message": str(e)}), 500
    

# ========================= PIGMY → COMPUTER ========================= #

SESSION_USER = "system"  # Temporary


# -------- LOAD PENDING TRANSACTIONS (status=0) -------- #

@app.route("/api/pig-to-com/load", methods=["POST"])
@require_login
def api_load_pending_for_agent():
    data = request.json or {}

    agent_number = data.get("agent_number")
    deposit_type = data.get("deposit_type")

    if not agent_number or not deposit_type:
        return jsonify({"error": "agent_number and deposit_type required"}), 400

    # Get branch codes from local DB
    local_branch_code, remote_branch_code = get_branch_codes()

    try:
        conn = remote_connection()
        cur = conn.cursor(dictionary=True)

        cur.execute("""
            SELECT id, account_number, account_name, date, amount
            FROM transactions
            WHERE branch_code=%s
              AND agent_code=%s
              AND status=0
            ORDER BY date, id
        """, (remote_branch_code, agent_number))

        rows = cur.fetchall()
        conn.close()

        cleaned = []
        for r in rows:
            cleaned.append({
                "id": r["id"],
                "accnum": r["account_number"],
                "name": r["account_name"],
                "date": str(r["date"]),
                "amount": float(r["amount"])
            })

        return jsonify({
            "count": len(cleaned),
            "transactions": cleaned,
            "remote_branch_code": remote_branch_code,
            "local_branch_code": local_branch_code
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 500


# -------- DOWNLOAD → SAVE TO LOCAL + UPDATE REMOTE -------- #

@app.route("/api/pig-to-com/download", methods=["POST"])
@require_login
def api_save_pending_to_local():
    data = request.json or {}

    agent_number = data.get("agent_number")
    deposit_type = data.get("deposit_type")
    posting_date = data.get("transaction_date")
    txns = data.get("transactions")

    if not agent_number or not deposit_type or not txns or not posting_date:
        return jsonify({"error": "Missing fields"}), 400

    # Get branch codes
    local_branch_code, remote_branch_code = get_branch_codes()

    try:
        local = local_connection()
        lcur = local.cursor()

        remote = remote_connection()
        rcur = remote.cursor(dictionary=True)

        total_amount = 0

        # Get agent name (optional but needed for backuptransaction)
        rcur.execute("""
            SELECT agent_name FROM agent
            WHERE agent_code=%s AND branch_code=%s
        """, (agent_number, remote_branch_code))
        agent_row = rcur.fetchone()
        agent_name = agent_row["agent_name"] if agent_row else "Unknown"

        for t in txns:
            accnum = t["accnum"]
            amount = float(t["amount"])
            tradate = t["date"]
            txn_id = t["id"]

            # ---- Save to LOCAL pigmyaccountposting ----
            lcur.execute("""
                INSERT INTO pigmyaccountposting
                (agenum, accnum, typofpig, credit, vounum, traby,
                 creusenam, tradat, debit, bracod, chenum, nar)
                VALUES (%s, %s, %s, %s, %s, %s,
                        %s, %s, %s, %s, %s, %s)
            """, (
                agent_number, accnum, deposit_type,
                amount, 'online pigmy', '1',
                SESSION_USER, posting_date, '0', local_branch_code, '0', f'OMPC Deposit : {tradate}'
            ))

            # ---- Fetch full transaction data before update/deletion ----
            rcur.execute("""
                SELECT transaction_id, branch_code, agent_code,
                       account_number, account_name, date, time, amount
                FROM transactions WHERE id=%s
            """, (txn_id,))
            txndata = rcur.fetchone()

            # ---- Backup Transaction into backuptransaction ----
            if txndata:
                rcur.execute("""
                    INSERT INTO backuptransaction
                    (transaction_id, branch_code, branch_name, agent_code, agent_name,
                     account_number, account_name, date, time, amount)
                    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
                """, (
                    txndata["transaction_id"],
                    txndata["branch_code"],
                    session.get("branch_name"),  # from login session
                    txndata["agent_code"],
                    agent_name,
                    txndata["account_number"],
                    txndata["account_name"],
                    txndata["date"],
                    txndata["time"],
                    txndata["amount"]
                ))

            # ---- Mark transaction as processed ----
            rcur.execute("UPDATE transactions SET status=1 WHERE id=%s", (txn_id,))

            total_amount += amount

        # ---- Delete all processed (status=1) transactions for this agent ----
        rcur.execute("""
            DELETE FROM transactions
            WHERE agent_code=%s AND branch_code=%s AND status=1
        """, (agent_number, remote_branch_code))

        # ---- Delete accounts for this agent ----
        rcur.execute("""
            DELETE FROM accounts
            WHERE agent_code=%s AND branch_code=%s
        """, (agent_number, remote_branch_code))

        # ---- Commit all ----
        local.commit()
        remote.commit()

        local.close()
        remote.close()

        return jsonify({
            "success": True,
            "count": len(txns),
            "total_amount": total_amount,
            "local_branch_code": local_branch_code,
            "remote_branch_code": remote_branch_code
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 500


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
    import sys
    import traceback
    
    # Determine if running as frozen exe (PyInstaller)
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
            
            # Running as exe: Start Flask in background thread + show tray
            from tray_manager import TrayManager
            from werkzeug.serving import make_server
            
            with open(log_file, "a") as f:
                f.write("Imports successful\n")
            
            # Create a non-debug WSGI server
            wsgi_server = make_server("127.0.0.1", 5000, app)
            
            with open(log_file, "a") as f:
                f.write("Flask server created\n")
            
            # Run Flask in a background thread
            flask_thread = threading.Thread(target=wsgi_server.serve_forever, daemon=True)
            flask_thread.start()
            
            with open(log_file, "a") as f:
                f.write("Flask thread started\n")
            
            # Wait a moment for Flask to start
            Timer(0.5, lambda: None).start()
            
            # Show tray icon in main thread (blocks until exit)
            with open(log_file, "a") as f:
                f.write("Starting tray manager\n")
            
            tray = TrayManager(app, flask_thread)
            tray.run()
            
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
        # Running as script: Start Flask normally with auto-open browser
        Timer(1, open_browser).start()
        app.run(debug=False, host="127.0.0.1", port=5000)
