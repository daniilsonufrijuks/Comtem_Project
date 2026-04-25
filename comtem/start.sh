#!/bin/bash
php artisan migrate --force --no-interaction --path=database/migrations/2026_04_25_092457_import_initial_data_from_sql_dump.php
php artisan config:cache
php artisan route:cache
php artisan view:cache
service apache2 start
php-fpm -F
