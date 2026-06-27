@echo off
cd /d "%~dp0"
start "" pythonw run_app.py --gui --target browser
exit
