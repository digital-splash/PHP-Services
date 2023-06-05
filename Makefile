TTY_PARAM := $(shell tty > /dev/null && echo "" || echo "-T")
WINPTY := $(shell command -v winpty && echo "winpty " ||  echo "")

start: stop
	docker-compose up -d
	$(WINPTY)docker-compose exec $(TTY_PARAM) php-services-base bash -c "composer install --no-plugins --no-scripts --no-interaction"

install:
	$(WINPTY)docker-compose exec $(TTY_PARAM) php-services-base bash -c "composer install --no-plugins --no-scripts --no-interaction"

stop:
	docker-compose down

bash:
	$(WINPTY)docker-compose exec $(TTY_PARAM) php-services-base bash

logs:
	docker-compose logs php-services-base

followlogs:
	docker-compose logs -f php-services-base

status:
	docker-compose ps

phpunit:
	$(WINPTY)docker-compose exec $(TTY_PARAM) php-services-base bash -c "cd /var/www/html && composer run phpunit"

composer:
	$(WINPTY)docker-compose exec $(TTY_PARAM) php-services-base bash -c "composer dump-autoload"
