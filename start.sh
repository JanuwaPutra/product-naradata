#!/bin/bash

# Set folder permission
chmod -R 775 storage bootstrap/cache

# Jalankan migrasi otomatis
php artisan migrate --force

# Jalankan Laravel di port Railway
php artisan serve --host=0.0.0.0 --port=${PORT}
