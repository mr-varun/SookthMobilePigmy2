import mysql.connector
import pyodbc
import os
import traceback
from datetime import datetime

# Log file
LOG_FILE = os.path.join(os.path.expanduser("~"), "SookthMobilePigmy_debug.log")

def log_error(msg):
    try:
        with open(LOG_FILE, "a") as f:
            f.write(f"[{datetime.now().isoformat()}] {msg}\n")
    except:
        pass


# ============================================================
#   LOCAL DATABASE → MS ACCESS (.mdb / .accdb)
# ============================================================


def local_connection():
    """Return MS Access DB connection using DSN."""
    try:
        conn = pyodbc.connect(
            r"DSN=smp;",
            autocommit=True
        )

        log_error("Local Access DB Connection via DSN: SUCCESS")
        return conn

    except Exception as err:
        msg = f"Local Access DB Connection Error (DSN): {err}"
        print(msg)
        log_error(msg)
        log_error(traceback.format_exc())
        return None



# ============================================================
#   REMOTE DATABASE → MYSQL (NO CHANGE)
# ============================================================

def remote_connection():
    try:
        conn = mysql.connector.connect(
            host="srv1749.hstgr.io",
            user="u936458195_mobile_pigmy",
            password="P4c|5LMb?CE",
            database="u936458195_mobile_pigmy",
            port=3306
        )
        log_error("Remote MySQL DB Connection: SUCCESS")
        return conn

    except mysql.connector.Error as err:
        msg = f"Remote DB Connection Error: {err}"
        print(msg)
        log_error(msg)
        log_error(traceback.format_exc())
        return None

    except Exception as e:
        msg = f"Remote DB Unexpected Error: {e}"
        print(msg)
        log_error(msg)
        log_error(traceback.format_exc())
        return None
