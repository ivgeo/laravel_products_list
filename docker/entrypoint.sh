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

FIRST_INIT=false
if [ ! -f .env ]; then
    echo "No .env found, copying .env.example -> .env"
    cp .env.example .env
    FIRST_INIT=true
fi

if ! grep -q '^APP_KEY=.\+' .env; then
    php artisan key:generate --ansi
fi

if [ "$FIRST_INIT" = "true" ]; then
    echo "First initialization: running migrate:fresh --seed"
    php artisan migrate:fresh --seed --force
else
    php artisan migrate --force
fi

exec "$@"