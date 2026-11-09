@echo off
setlocal
cd /d "%~dp0"
title BookSwap API
if exist runtime\php\php.exe set "PATH=%CD%\runtime\php;%PATH%"
where php >nul 2>&1 || (echo [ERREUR] PHP 8.3 ou superieur est requis. Consultez README.md.& pause & exit /b 1)
php -r "exit(version_compare(PHP_VERSION,'8.3.0','>=')?0:1);" || (echo [ERREUR] PHP 8.3 minimum est requis.& pause & exit /b 1)
php -m | findstr /i /x "pdo_sqlite" >nul || (echo [ERREUR] Activez extension=pdo_sqlite et extension=sqlite3 dans php.ini.& pause & exit /b 1)
if exist vendor\autoload.php goto vendor_ok
where composer >nul 2>&1 || (echo [ERREUR] Composer est requis pour installer vendor.& pause & exit /b 1)
composer install --no-interaction || (echo [ERREUR] Installation Composer impossible.& pause & exit /b 1)
:vendor_ok
if not exist .env copy .env.example .env >nul
findstr /b "APP_KEY=base64:" .env >nul || php artisan key:generate
if not exist database\database.sqlite type nul > database\database.sqlite
php artisan migrate --force || (echo [ERREUR] Migration impossible.& pause & exit /b 1)
php artisan l5-swagger:generate || (echo [ERREUR] Generation Swagger impossible.& pause & exit /b 1)
netstat -ano | findstr /r /c:":8000 .*LISTENING" >nul && (echo [ERREUR] Le port 8000 est deja utilise. Fermez le programme concerne.& pause & exit /b 1)
echo API: http://127.0.0.1:8000/api/v1/exchange-requests
echo Swagger: http://127.0.0.1:8000/api/documentation
start "" "http://127.0.0.1:8000/api/documentation"
php artisan serve --host=127.0.0.1 --port=8000
pause
