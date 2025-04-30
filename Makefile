up:
	docker compose up -d

down:
	docker compose down

restart: down up

install:
	docker compose exec php composer install
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

migrate:
	docker compose exec php php bin/console doctrine:migrations:migrate

bash:
	docker compose exec php bash

cache-clear:
	docker compose exec php php bin/console cache:clear

test:
	docker compose exec php ./vendor/bin/phpunit
