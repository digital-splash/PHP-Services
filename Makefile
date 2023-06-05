include ./dockerfiles/.env

help:
	@echo "Commands:"
	@echo "  build               Build Docker Images"
	@echo "  start               Start Docker Containers"
	@echo "  stop                Stop and Clear all Docker Containers"
	@echo "  back                Enter Bash Mode"
	@echo "  logs                Print Docker Logs"
	@echo "  followlogs          Print Docker Logs with Follow (will continue streaming the new output from the container’s STDOUT and STDERR)"
	@echo "  status              Print Docker Status"
	@echo "  phpunit             Run all PHP Units"
	@echo "  composer-install    Reinstall Composer"
	@echo "  composer-dump       Run Composer Dump Autoload"

build:
	docker build ./dockerfiles/base-container -f ./dockerfiles/base-container/Dockerfile -t $(BASE_IMAGE_NAME):latest --no-cache

start: stop
	docker-compose up -d
	docker-compose exec $(BASE_IMAGE_NAME) bash -c "composer install --no-plugins --no-scripts --no-interaction"

stop:
	docker-compose down

bash:
	docker-compose exec $(BASE_IMAGE_NAME) bash

logs:
	docker-compose logs $(BASE_IMAGE_NAME)

followlogs:
	docker-compose logs -f $(BASE_IMAGE_NAME)

status:
	docker-compose ps

phpunit:
	docker-compose exec $(BASE_IMAGE_NAME) bash -c "cd /var/www/html && composer run phpunit"

phpunit:
	docker-compose exec $(BASE_IMAGE_NAME) bash -c "composer install --no-plugins --no-scripts --no-interaction"

composer-dump:
	docker-compose exec $(BASE_IMAGE_NAME) bash -c "composer dump-autoload"
