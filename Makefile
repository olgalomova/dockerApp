NAME = user_management_app
DOCKER_COMPOSE = docker-compose
DATA_DIR = backend/data

all:
	@printf "Launching $(NAME)...\n"
	@$(MAKE) setup
	@$(DOCKER_COMPOSE) up -d
	@printf "Application started\n"

build:
	@printf "Building $(NAME)...\n"
	@$(MAKE) setup
	@$(DOCKER_COMPOSE) up -d --build
	@printf "Application built and started\n"

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
	@printf "Rebuilding $(NAME)...\n"
	@$(MAKE) setup
	@$(DOCKER_COMPOSE) up -d --build
	@printf "Application rebuilt and started\n"

clean: down
	@printf "Cleaning $(NAME)...\n"
	@docker system prune -a -f
	@printf "Docker system cleaned\n"

fclean: down
	@printf "Full clean of $(NAME)...\n"
	@docker system prune --all --force --volumes
	@docker network prune --force
	@docker volume prune --force
	@rm -rf $(DATA_DIR)/*.sqlite
	@printf "Full clean completed\n"

reset-db:
	@printf "Resetting database...\n"
	@rm -f $(DATA_DIR)/database.sqlite
	@$(DOCKER_COMPOSE) restart php
	@printf "Database reset. New database will be created on next request\n"

.PHONY: all build down stop start restart re clean fclean reset-db
