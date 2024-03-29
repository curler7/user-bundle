#/bin/bash

run=docker-compose exec php

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
	${run} composer prepare-orm-test
	${run} composer load-fixtures-test
	 ${run} vendor/bin/simple-phpunit

# Run single test: make test args="IndexTest.php"
.PHONY: test
test:
	make up
	${run} composer prepare-orm-test
	${run} composer load-fixtures-test
	${run} vendor/bin/simple-phpunit --filter=${args}

# Run composer with args. make composer args="..."
.PHONY: composer
composer:
	make up
	${run} composer ${args}

# Run composer update
.PHONY: composer-update
composer-update:
	make composer args=update

# Run composer require with args. make composer-require args="..."
.PHONY: composer-require
composer-require:
	make composer args="require ${args}"

# Run symfony console with args. make console args="..."
.PHONY: console
console:
	make up
	${run} fixtures/bin/console ${args}

# Run symfony console with args. make console args="..."
.PHONY: console-cache-clear
console-cache-clear:
	make console args=cache:clear

# Run git push.
.PHONY: git-push
git-push:
	git add -A
	git commit -m "s"
	git push origin master
