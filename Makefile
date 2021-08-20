#/bin/bash

RUN=docker-compose exec php

# Build local images
.PHONY: build
build:
	 docker-compose build

# Run containers as demon
.PHONY: up
up:
	 docker-compose up -d

# Stops all containers
.PHONY: stop
stop:
	docker-compose stop

# Down all containers and remove volumes
.PHONY: down
down:
	docker-compose down -v

# Stops all containers and removes all local images
.PHONY: remove
remove:
	make down
	docker rmi curler7/user-bundle_php

# Run all tests
.PHONY: tests
tests:
	make up
	 ${RUN} vendor/bin/simple-phpunit

# Run single test: make test TEST="IndexTest.php"
.PHONY: test
test:
	make up
	${RUN} vendor/bin/simple-phpunit -c ./ ${TEST}

# Run composer with args. make composer ARGS="..."
.PHONY: composer
composer:
	make up
	${RUN} composer ${ARGS}

# Run composer update
.PHONY: composer-update
composer-update:
	make composer ARGS="update"

# Run composer require with args. make composer-require BUNDLE="..."
.PHONY: composer-require
composer-require:
	make composer ARGS="require ${BUNDLE}"
