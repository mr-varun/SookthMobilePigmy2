# Desktop Applications

This directory contains desktop application versions of the Mobile Pigmy system.

## Directory Structure

### MDB/
Contains the Microsoft Access database version:
- `app.py` - Main Flask application
- `mobile_pigmy.mdb` - MS Access database file
- `connection.py` - Database connection handler
- `tray_manager.py` - System tray manager
- `updater.py` - Auto-update functionality
- `build.bat` - Build script to create executable
- `SMP.spec` - PyInstaller specification
- `requirements.txt` - Python dependencies

### SQL/
Contains the MySQL database version:
- `app.py` - Main Flask application
- `connection.py` - MySQL connection handler
- `tray_manager.py` - System tray manager
- `updater.py` - Auto-update functionality
- `build.bat` - Build script to create executable
- `SMP.spec` - PyInstaller specification
- `requirements.txt` - Python dependencies

## Migration Instructions

1. Copy MDB version:
   ```bash
   xcopy /E /I "SMP\Desktop\MDB" "mobile-pigmy-app\desktop-apps\MDB"
   ```

2. Copy SQL version:
   ```bash
   xcopy /E /I "SMP\Desktop\SQL" "mobile-pigmy-app\desktop-apps\SQL"
   ```

## Building Desktop Apps

Both versions use PyInstaller to create standalone executables:

```bash
cd desktop-apps/MDB
python -m pip install -r requirements.txt
build.bat
```

The compiled executable will be in `build/SMP/SMP.exe`

## Note

These directories are currently placeholders. Copy the actual desktop applications from the SMP/Desktop folder.
