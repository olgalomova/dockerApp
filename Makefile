NAME = user_management_app
DOCKER_COMPOSE = docker-compose
DATA_DIR = backend/data

all:
	@printf "🚀 Launching $(NAME)...\n"
	@$(MAKE) setup
	@$(DOCKER_COMPOSE) up -d
	@printf "✅ Application started at http://localhost:8080\n"

build:
	@printf "🔨 Building $(NAME)...\n"
	@$(MAKE) setup
	@$(DOCKER_COMPOSE) up -d --build
	@printf "✅ Application built and started at http://localhost:8080\n"

setup:
	@printf "📁 Creating necessary directories...\n"
	@mkdir -p $(DATA_DIR)
	@chmod 777 $(DATA_DIR)

down:
	@printf "⏹️  Stopping $(NAME)...\n"
	@$(DOCKER_COMPOSE) down

stop:
	@printf "⏸️  Stopping containers...\n"
	@$(DOCKER_COMPOSE) stop

start:
	@printf "▶️  Starting containers...\n"
	@$(DOCKER_COMPOSE) start

restart:
	@printf "🔄 Restarting $(NAME)...\n"
	@$(DOCKER_COMPOSE) restart

re: down
	@printf "🔄 Rebuilding $(NAME)...\n"
	@$(MAKE) setup
	@$(DOCKER_COMPOSE) up -d --build
	@printf "✅ Application rebuilt and started\n"

logs:
	@$(DOCKER_COMPOSE) logs -f

logs-php:
	@$(DOCKER_COMPOSE) logs -f php

logs-nginx:
	@$(DOCKER_COMPOSE) logs -f nginx

ps:
	@$(DOCKER_COMPOSE) ps

clean: down
	@printf "🧹 Cleaning $(NAME)...\n"
	@docker system prune -a -f
	@printf "✅ Docker system cleaned\n"

fclean: down
	@printf "🗑️  Full clean of $(NAME)...\n"
	@docker system prune --all --force --volumes
	@docker network prune --force
	@docker volume prune --force
	@rm -rf $(DATA_DIR)/*.sqlite
	@printf "✅ Full clean completed\n"

reset-db:
	@printf "🗄️  Resetting database...\n"
	@rm -f $(DATA_DIR)/database.sqlite
	@$(DOCKER_COMPOSE) restart php
	@printf "✅ Database reset. New database will be created on next request\n"

shell-php:
	@docker exec -it php_app /bin/bash

shell-nginx:
	@docker exec -it nginx_app /bin/sh

help:
	@printf "Available commands:\n"
	@printf "  make all        - Start the application\n"
	@printf "  make build      - Build and start the application\n"
	@printf "  make down       - Stop and remove containers\n"
	@printf "  make stop       - Stop containers without removing\n"
	@printf "  make start      - Start stopped containers\n"
	@printf "  make restart    - Restart containers\n"
	@printf "  make re         - Rebuild and restart\n"
	@printf "  make logs       - Show logs (all services)\n"
	@printf "  make logs-php   - Show PHP logs\n"
	@printf "  make logs-nginx - Show Nginx logs\n"
	@printf "  make ps         - Show running containers\n"
	@printf "  make clean      - Clean Docker system\n"
	@printf "  make fclean     - Full clean (removes database)\n"
	@printf "  make reset-db   - Reset database\n"
	@printf "  make shell-php  - Open shell in PHP container\n"
	@printf "  make shell-nginx- Open shell in Nginx container\n"

.PHONY: all build setup down stop start restart re logs logs-php logs-nginx ps clean fclean reset-db shell-php shell-nginx help
