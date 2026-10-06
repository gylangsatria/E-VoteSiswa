#!/bin/sh
set -e

mkdir -p /var/www/html/uploads

for d in application/cache application/logs uploads asset/img; do
    if [ -d "/var/www/html/$d" ]; then
        chown -R www-data:www-data "/var/www/html/$d"
    fi
done

mkdir -p /tmp/sessions
chown -R www-data:www-data /tmp/sessions

exec apache2-foreground
