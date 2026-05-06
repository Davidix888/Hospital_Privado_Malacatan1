#!/bin/sh
set -e

cd /var/www/html

# Asegura permisos de escritura para Laravel
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

exec "$@"
