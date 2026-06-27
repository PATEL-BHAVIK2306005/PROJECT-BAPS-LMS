@echo off
cd /d "%~dp0"
start "" pythonw run_app.py --gui --target desktop
exit
