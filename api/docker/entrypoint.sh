#!/bin/sh
set -e

mkdir -p /var/pixario/uploads
chown -R www-data:www-data /var/pixario/uploads
chmod -R 700 /var/pixario/uploads

php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

exec "$@"