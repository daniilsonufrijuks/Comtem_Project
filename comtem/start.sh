#!/bin/bash
php artisan migrate --force --no-interaction
php artisan config:cache
php artisan route:cache
php artisan view:cache
service apache2 start
php-fpm -F
