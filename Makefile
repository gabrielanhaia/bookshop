help:
	@echo "Usage: make [command]"
	@echo ""
	@echo "Commands:"
	@echo "  setup            Setup the project (run this command after cloning the project)"
	@echo "  start-container  Start the docker container"
	@echo "  stop-container   Stop the docker container"
	@echo "  enter-container  Enter the docker container"
	@echo "  test-unit        Run unit tests"
	@echo "  test-architecture Run architecture tests"
	@echo "  test-all         Run all tests"
	@echo "  install-deps     Install dependencies"
	@echo "  clear            Clear cache"
	@echo "  migrate          Run migrations"

# Setup (run this command after cloning the project)
setup:
	cp phpstan.dist.neon phpstan.neon
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

test-all:
	test-architecture
	test-unit

# Composer commands
install-deps:
	php composer install

# Other commands
clear:
	php bin/console cache:clear

migrate:
	php bin/console doctrine:migrations:migrate
