#!/bin/sh
set -e

if [ "$DB_CONNECTION" = "mysql" ] && [ -n "$DB_HOST" ]; then
    echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT:-3306}..."
    until php -r "exit(@fsockopen('${DB_HOST}', ${DB_PORT:-3306}) ? 0 : 1);"; do
        sleep 1
    done
    echo "MySQL is up."
fi

mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --no-progress --prefer-dist
fi

if [ -z "$APP_KEY" ] && [ -f .env ]; then
    php artisan key:generate --ansi
fi

exec "$@"