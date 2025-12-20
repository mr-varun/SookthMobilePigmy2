# updater.py
"""
Auto-update module for Sookth Mobile Pigmy Banking application.
Checks for updates from server and downloads new version if available.
"""

import os
import sys
import subprocess
import requests
import tempfile
import shutil
from threading import Thread
import time

# -------------------- VERSION CONFIGURATION -------------------- #
# UPDATE THIS VERSION NUMBER BEFORE BUILDING NEW RELEASE
CURRENT_VERSION = "1.2.2"
UPDATE_SERVER_URL = "https://mobilepigmy.sookthsolutions.com/Desktop/MDB"
VERSION_FILE_URL = f"{UPDATE_SERVER_URL}/version.txt"
EXE_FILE_URL = f"{UPDATE_SERVER_URL}/dist/SMP.exe"

# -------------------- UPDATE CHECKER -------------------- #

def get_server_version():
    """Fetch the latest version from server.
    
    Returns:
        str: Latest version string, or None if unable to fetch
    """
    try:
        response = requests.get(VERSION_FILE_URL, timeout=10)
        if response.status_code == 200:
            version = response.text.strip()
            return version
        else:
            print(f"Failed to fetch version: HTTP {response.status_code}")
            return None
    except Exception as e:
        print(f"Error fetching version: {e}")
        return None


def compare_versions(current, server):
    """Compare two version strings.
    
    Args:
        current (str): Current version (e.g., "1.1.0")
        server (str): Server version (e.g., "1.2.0")
    
    Returns:
        bool: True if server version is newer, False otherwise
    """
    try:
        current_parts = [int(x) for x in current.split('.')]
        server_parts = [int(x) for x in server.split('.')]
        
        # Pad shorter version with zeros
        while len(current_parts) < len(server_parts):
            current_parts.append(0)
        while len(server_parts) < len(current_parts):
            server_parts.append(0)
        
        # Compare each part
        for c, s in zip(current_parts, server_parts):
            if s > c:
                return True
            elif s < c:
                return False
        
        return False  # Versions are equal
    except Exception as e:
        print(f"Error comparing versions: {e}")
        return False


def check_for_updates():
    """Check if a new version is available.
    
    Returns:
        tuple: (bool, str) - (update_available, server_version)
    """
    server_version = get_server_version()
    
    if server_version is None:
        return False, None
    
    update_available = compare_versions(CURRENT_VERSION, server_version)
    
    return update_available, server_version


# -------------------- UPDATE DOWNLOADER -------------------- #

def download_update(progress_callback=None):
    """Download the latest version from server.
    
    Args:
        progress_callback (callable): Optional callback for download progress
    
    Returns:
        str: Path to downloaded file, or None if failed
    """
    try:
        # Create temporary file
        temp_dir = tempfile.gettempdir()
        temp_file = os.path.join(temp_dir, "SMP_update.exe")
        
        print(f"Downloading update from {EXE_FILE_URL}...")
        
        # Download with progress tracking
        response = requests.get(EXE_FILE_URL, stream=True, timeout=60)
        
        if response.status_code != 200:
            print(f"Failed to download: HTTP {response.status_code}")
            return None
        
        total_size = int(response.headers.get('content-length', 0))
        downloaded = 0
        
        with open(temp_file, 'wb') as f:
            for chunk in response.iter_content(chunk_size=8192):
                if chunk:
                    f.write(chunk)
                    downloaded += len(chunk)
                    
                    if progress_callback and total_size > 0:
                        progress = int((downloaded / total_size) * 100)
                        progress_callback(progress)
        
        print(f"Download complete: {temp_file}")
        return temp_file
    
    except Exception as e:
        print(f"Error downloading update: {e}")
        return None


# -------------------- UPDATE INSTALLER -------------------- #

def install_update(new_exe_path):
    """Replace current executable with new version and restart.
    
    Args:
        new_exe_path (str): Path to the downloaded new executable
    
    Returns:
        bool: True if installation initiated successfully
    """
    try:
        if not getattr(sys, 'frozen', False):
            print("Not running as frozen executable - update not applicable")
            return False
        
        current_exe = sys.executable
        backup_exe = current_exe + ".old"
        
        # Create batch script to replace exe and restart
        batch_script = f"""@echo off
echo Updating Sookth Mobile Pigmy Banking...
timeout /t 2 /nobreak >nul

REM Backup current version
if exist "{backup_exe}" del "{backup_exe}"
move "{current_exe}" "{backup_exe}"

REM Copy new version
move /Y "{new_exe_path}" "{current_exe}"

REM Start new version
start "" "{current_exe}"

REM Clean up
timeout /t 2 /nobreak >nul
if exist "{backup_exe}" del "{backup_exe}"
del "%~f0"
"""
        
        # Write batch script to temp file
        batch_file = os.path.join(tempfile.gettempdir(), "smp_update.bat")
        with open(batch_file, 'w') as f:
            f.write(batch_script)
        
        # Execute batch script and exit current process
        subprocess.Popen([batch_file], shell=True, creationflags=subprocess.CREATE_NO_WINDOW)
        
        # Give batch time to start
        time.sleep(1)
        
        # Exit current application
        os._exit(0)
        
        return True
    
    except Exception as e:
        print(f"Error installing update: {e}")
        return False


# -------------------- BACKGROUND UPDATE CHECK -------------------- #

def check_and_update_background(callback=None):
    """Check for updates in background thread and notify via callback.
    
    Args:
        callback (callable): Function to call with update status
                             callback(available: bool, version: str)
    """
    def _check():
        update_available, server_version = check_for_updates()
        if callback:
            callback(update_available, server_version)
    
    thread = Thread(target=_check, daemon=True)
    thread.start()


def perform_update(progress_callback=None):
    """Download and install update.
    
    Args:
        progress_callback (callable): Optional callback for progress updates
    
    Returns:
        bool: True if update initiated successfully
    """
    # Download update
    new_exe_path = download_update(progress_callback)
    
    if new_exe_path is None:
        return False
    
    # Install update (this will restart the application)
    return install_update(new_exe_path)


# -------------------- UTILITY FUNCTIONS -------------------- #

def get_current_version():
    """Get the current application version.
    
    Returns:
        str: Current version string
    """
    return CURRENT_VERSION


def get_update_info():
    """Get detailed update information.
    
    Returns:
        dict: Update information including current and server versions
    """
    update_available, server_version = check_for_updates()
    
    return {
        "current_version": CURRENT_VERSION,
        "server_version": server_version,
        "update_available": update_available,
        "update_url": EXE_FILE_URL
    }
