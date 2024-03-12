include ./dockerfiles/.env

help:
	@echo "Commands:"
	@echo "  build               Build Docker Images"
	@echo "  build-nocache       Build Docker Images without Cache"
	@echo "  start               Start Docker Containers"
	@echo "  stop                Stop and Clear all Docker Containers"
	@echo "  bash                Enter App Bash Mode"
	@echo "  bash-db             Enter Database Bash Mode"
	@echo "  logs                Print Docker Logs"
	@echo "  followlogs          Print Docker Logs with Follow (will continue streaming the new output from the container’s STDOUT and STDERR)"
	@echo "  status              Print Docker Status"
	@echo "  phpunit             Run all PHP Units"
	@echo "  composer-install    Reinstall Composer"
	@echo "  composer-dump       Run Composer Dump Autoload"
	@echo "  init-db             Initialize Database"

build: stop
	docker build ./dockerfiles/base-container -t $(BASE_IMAGE_NAME):latest

build-nocache:
	docker build ./dockerfiles/base-container -t $(BASE_IMAGE_NAME):latest --no-cache

start: stop
	docker-compose up -d

stop:
	docker-compose down

bash:
	docker-compose exec $(BASE_IMAGE_NAME) bash

bash-db:
	docker-compose exec $(DB_IMAGE_NAME) bash

logs:
	docker-compose logs $(BASE_IMAGE_NAME)

followlogs:
	docker-compose logs -f $(BASE_IMAGE_NAME)

status:
	docker-compose ps

phpunit:
	docker-compose exec $(BASE_IMAGE_NAME) bash -c "cd /var/www/html && composer run phpunit"

composer-install:
	docker-compose exec $(BASE_IMAGE_NAME) bash -c "composer install --no-plugins --no-scripts --no-interaction"

composer-dump:
	docker-compose exec $(BASE_IMAGE_NAME) bash -c "composer dump-autoload"

init-db:
	docker-compose exec $(DB_IMAGE_NAME) bash -c "chmod +x docker-entrypoint-initdb.d/*"
	docker-compose exec $(DB_IMAGE_NAME) bash -c "/docker-entrypoint-initdb.d/init-main-db.sh"
