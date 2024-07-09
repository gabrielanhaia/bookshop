help:
	@echo "Usage: \033[0;32mmake\033[0m \033[0;31m[command]\033[0m"
	@echo ""
	@echo "Commands:"
	@echo "\033[0;31m  help\033[0m             Show this help message"
	@echo "\033[0;31m  setup\033[0m            Setup the project"
	@echo "\033[0;31m  start-container\033[0m  Start the docker container"
	@echo "\033[0;31m  stop-container\033[0m   Stop the docker container"
	@echo "\033[0;31m  enter-container\033[0m  Enter the docker container"
	@echo "\033[0;31m  test-unit\033[0m        Run the unit tests"
	@echo "\033[0;31m  test-architecture\033[0m Run the architecture tests"
	@echo "\033[0;31m  test-behat\033[0m       Run the behat tests"
	@echo "\033[0;31m  test-all\033[0m         Run all the tests"
	@echo "\033[0;31m  install-deps\033[0m     Install the dependencies"
	@echo "\033[0;31m  clear\033[0m            Clear the cache"
	@echo "\033[0;31m  migrate\033[0m          Run the migrations"

# Setup (run this command after cloning the project)
setup:
	echo "\033[0;33m Setting up the project... \033[0m"
	cp phpstan.dist.neon phpstan.neon
	cp phpunit.xml.dist phpunit.xml
	cp behat.yml.dist behat.yml
	make start-container
	docker compose exec php composer install
	docker compose exec php bin/console doctrine:migrations:migrate

# Docker commands
start-container:
	docker-compose up -d

stop-container:
	docker-compose down

enter-container:
	docker-compose exec php bash

# Tests
test-unit:
	php bin/phpunit --testsuite unit

test-architecture:
	php vendor/bin/phpstan analyse -c phpstan.neon

test-behat:
	APP_ENV=test vendor/bin/behat --strict --format progress

test-all:
	make test-architecture
	make test-unit
	make test-behat

# Composer commands
install-deps:
	php composer install

# Other commands
clear:
	php bin/console cache:clear

migrate:
	php bin/console doctrine:migrations:migrate
