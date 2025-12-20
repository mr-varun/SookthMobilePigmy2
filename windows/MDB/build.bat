@echo off
title Building Sookth Mobile Pigmy EXE...
echo =======================================
echo     Building SookthMobilePigmy.exe
echo =======================================

REM ---------------------------------------
REM CLEAN OLD BUILD FILES
REM ---------------------------------------
if exist build rmdir /s /q build
if exist dist rmdir /s /q dist
if exist *.spec del *.spec

REM ---------------------------------------
REM PYINSTALLER BUILD
REM ---------------------------------------
pyinstaller ^
  --onefile ^
  --noconsole ^
  --name SMP ^
  --icon=myicon.ico ^
  --add-data "templates;templates" ^
  --add-data "static;static" ^
  --add-data "myicon.ico;." ^
  --hidden-import connection ^
  --hidden-import tray_manager ^
  --hidden-import updater ^
  --hidden-import mysql.connector ^
  --hidden-import mysql.connector.connection ^
  --hidden-import mysql.connector.network ^
  --hidden-import mysql.connector.cursor ^
  --hidden-import mysql.connector.abstracts ^
  --hidden-import mysql.connector.catch23 ^
  --hidden-import mysql.connector.plugins ^
  --hidden-import mysql.connector.plugins.mysql_native_password ^
  --hidden-import pyodbc ^
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
echo ==========================================
echo BUILD COMPLETED SUCCESSFULLY!
echo Output EXE: dist\SookthMobilePigmy.exe
echo ==========================================
echo.
echo Debug logs will be saved at:
echo   %USERPROFILE%\SookthMobilePigmy_debug.log
echo.
pause
