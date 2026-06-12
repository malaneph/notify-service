#!/bin/bash
set -e

if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist
fi

if [ ! -f .env ]; then
    cp .env.example .env
fi

if ! grep -q "^APP_KEY=base64" .env; then
    php artisan key:generate --ansi --force
fi

if [ "$RUN_MIGRATIONS" = "true" ]; then
    php artisan migrate --force
fi

exec "$@"
