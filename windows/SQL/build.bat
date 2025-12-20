@echo off
REM Build one-file, no-console Windows exe using PyInstaller
REM Ensure you're in project root and in the correct Python environment

REM Delete old build artifacts
if exist build rmdir /s /q build
if exist dist rmdir /s /q dist
if exist *.spec del *.spec

pyinstaller --onefile --noconsole --name SMP --icon=myicon.ico ^
  --add-data "templates;templates" ^
  --add-data "static;static" ^
  --add-data "myicon.ico;." ^
  --hidden-import connection ^
  --hidden-import tray_manager ^
  --hidden-import updater ^
  --hidden-import mysql.connector ^
  --hidden-import mysql.connector.abstracts ^
  --hidden-import mysql.connector.catch23 ^
  --hidden-import mysql.connector.plugins ^
  --hidden-import mysql.connector.plugins.mysql_native_password ^
  --hidden-import pystray ^
  --hidden-import PIL ^
  --hidden-import requests ^
  --hidden-import werkzeug ^
  --hidden-import werkzeug.serving ^
  --collect-all mysql ^
  --collect-all pystray ^
  --collect-all flask ^
  app.py

echo.
echo Build finished. Output in dist\SookthMobilePigmy.exe
echo.
echo After running the exe, check for debug logs at: %USERPROFILE%\SookthMobilePigmy_debug.log
pause
