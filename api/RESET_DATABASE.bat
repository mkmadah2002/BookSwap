@echo off
setlocal
cd /d "%~dp0"
echo ATTENTION : toutes les donnees locales seront remplacees par la demonstration.
set /p CONFIRM=Tapez OUI pour continuer : 
if /i not "%CONFIRM%"=="OUI" (echo Annule.& exit /b 0)
php artisan migrate:fresh --seed --force || (echo [ERREUR] Reinitialisation impossible.& pause & exit /b 1)
echo Base BookSwap reinitialisee.
pause
