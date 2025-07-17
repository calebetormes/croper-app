#!/usr/bin/env bash
cd /home/barterapp-dev-croper/htdocs/croper-app || exit
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
