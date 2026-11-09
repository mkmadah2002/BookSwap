#!/usr/bin/env sh
set -eu
cd "$(dirname "$0")"
printf 'ATTENTION : toutes les données locales seront remplacées. Tapez OUI : '
read -r confirm
[ "$confirm" = "OUI" ] || { echo "Annulé."; exit 0; }
php artisan migrate:fresh --seed --force
echo "Base BookSwap réinitialisée."
