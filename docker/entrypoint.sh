#!/bin/sh
set -e

# vendor собран при сборке образа (/opt/vendor). При первом старте копируем его
# в ./vendor — это bind-mount на хост, так что IDE сразу видит зависимости.
if [ ! -d /opt/vendor ]; then
  echo "В образе нет /opt/vendor — пересоберите: docker compose build" >&2
  exit 1
fi
if [ ! -f vendor/autoload.php ]; then
  cp -a /opt/vendor ./vendor
fi

if [ ! -f .env ]; then
  cp .env.example .env
fi

if ! grep -q '^APP_KEY=..' .env; then
  php artisan key:generate --force
fi

mkdir -p database
touch database/database.sqlite

# Сидер не идемпотентный — пересеваем только пустую базу, дальше лишь migrate.
if [ ! -s database/database.sqlite ]; then
  php artisan migrate:fresh --seed --force
else
  php artisan migrate --force
fi

exec "$@"
