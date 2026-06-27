@echo off
title Deela LMS - Push to GitHub
cd /d "%~dp0"
echo ===================================================
echo             DEELA LMS - GITHUB DEPLOYER
echo ===================================================
echo.
echo Pushing commits to: https://github.com/PATEL-BHAVIK2306005/PROJECT-BAPS-LMS.git
echo.
git push -f origin main
echo.
echo ===================================================
echo Done! If it succeeded, you can close this window.
echo ===================================================
pause
