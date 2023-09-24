.PHONY: *
DOCKER_EXEC_APP=docker compose exec app env XDEBUG_TRIGGER=off

check:
	@${DOCKER_EXEC_APP} ./vendor/bin/psalm

composer-install:
	@${DOCKER_EXEC_APP} composer install

down:
	@docker compose down

fix:
	@${DOCKER_EXEC_APP} ./vendor/bin/php-cs-fixer fix

pre-commit: check fix test

restart: down up

test:
	@${DOCKER_EXEC_APP} ./bin/phpunit

test-functional:
	@${DOCKER_EXEC_APP} ./bin/phpunit --testsuite "Functional Tests"

test-unit:
	@${DOCKER_EXEC_APP} ./bin/phpunit --testsuite "Unit Tests"

up:
	@docker compose up -d
