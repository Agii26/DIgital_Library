#!/bin/bash
php artisan migrate --force
php artisan db:seed --force
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan storage:link 2>/dev/null || true
php -S 0.0.0.0:8080 public/server.php