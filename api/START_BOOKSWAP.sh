#!/usr/bin/env sh
set -eu
cd "$(dirname "$0")"
command -v php >/dev/null 2>&1 || { echo "ERREUR : PHP 8.3+ est requis."; exit 1; }
php -r "exit(version_compare(PHP_VERSION,'8.3.0','>=')?0:1);" || { echo "ERREUR : PHP 8.3 minimum est requis."; exit 1; }
php -m | grep -qi '^pdo_sqlite$' || { echo "ERREUR : activez pdo_sqlite et sqlite3."; exit 1; }
if [ ! -f vendor/autoload.php ]; then command -v composer >/dev/null 2>&1 || { echo "ERREUR : Composer est requis."; exit 1; }; composer install --no-interaction; fi
[ -f .env ] || cp .env.example .env
grep -q '^APP_KEY=base64:' .env || php artisan key:generate
[ -f database/database.sqlite ] || : > database/database.sqlite
php artisan migrate --force
php artisan l5-swagger:generate
if command -v lsof >/dev/null 2>&1 && lsof -iTCP:8000 -sTCP:LISTEN >/dev/null 2>&1; then echo "ERREUR : le port 8000 est déjà utilisé."; exit 1; fi
(command -v xdg-open >/dev/null 2>&1 && xdg-open http://127.0.0.1:8000/api/documentation >/dev/null 2>&1 &) || (command -v open >/dev/null 2>&1 && open http://127.0.0.1:8000/api/documentation &)
php artisan serve --host=127.0.0.1 --port=8000
