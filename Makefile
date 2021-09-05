SHELL := /bin/bash

tests: export APP_ENV=test
tests:
	symfony console doctrine:database:drop --force || true
	symfony console doctrine:database:create
	symfony console doctrine:migrations:migrate -n
	symfony console doctrine:fixtures:load -n
	symfony php bin/phpunit $@
.PHONY:tests

start: ## Starts the server, the docker containers, and the message consumer worker
	symfony server:start -d
	docker-compose up -d
	symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async

yarn-watch:
	symfony run -d yarn encore dev --watch

migrate-create:
	symfony console make:migration

migrate:
	symfony console doctrine:migrations:migrate
