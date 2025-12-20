import mysql.connector
import os
import traceback
from datetime import datetime

# Log file for debugging (writes to user's temp or home directory)
LOG_FILE = os.path.join(os.path.expanduser("~"), "SookthMobilePigmy_debug.log")

def log_error(msg):
    """Write error message to a log file."""
    try:
        with open(LOG_FILE, "a") as f:
            f.write(f"[{datetime.now().isoformat()}] {msg}\n")
    except:
        pass

def local_connection():
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="bankdb",
            port=3306
        )
        log_error("Local DB Connection: SUCCESS")
        return conn
    except mysql.connector.Error as err:
        error_msg = f"Local DB Connection Error: {err}"
        print(error_msg)
        log_error(error_msg)
        log_error(traceback.format_exc())
        return None
    except Exception as e:
        error_msg = f"Local DB Unexpected Error: {e}"
        print(error_msg)
        log_error(error_msg)
        log_error(traceback.format_exc())
        return None

def remote_connection():
    try:
        conn = mysql.connector.connect(
            host="srv1749.hstgr.io",
            user="u936458195_mobile_pigmy",
            password="Vg9+hP;FG",
            database="u936458195_mobile_pigmy",
            port=3306
        )
        log_error("Remote DB Connection: SUCCESS")
        return conn
    except mysql.connector.Error as err:
        error_msg = f"Remote DB Connection Error: {err}"
        print(error_msg)
        log_error(error_msg)
        log_error(traceback.format_exc())
        return None
    except Exception as e:
        error_msg = f"Remote DB Unexpected Error: {e}"
        print(error_msg)
        log_error(error_msg)
        log_error(traceback.format_exc())
        return None
