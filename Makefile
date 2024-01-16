start: ## start application
	docker-compose up --build

connect: ## connect to php-fpm
	docker exec -it baltbit-php-fpm-1 /bin/bash

connect-nginx: ## connect to nginx
	docker exec -it baltbit-nginx-1 /bin/ash

test: ## run all tests
	docker-compose run php-fpm vendor/bin/phpunit
