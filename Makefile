up:
	docker compose up -d

down:
	docker compose down

restart: down up

install:
	docker compose exec app composer install
	docker compose exec app php bin/console doctrine:migrations:migrate --no-interaction

migrate:
	docker compose exec app php bin/console doctrine:migrations:migrate

bash:
	docker compose exec app bash

cache-clear:
	docker compose exec app php bin/console cache:clear

test:
	docker compose exec app ./vendor/bin/phpunit
