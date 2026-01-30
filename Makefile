.PHONY: help install setup dev test lint fix clean

# Colors for output
GREEN  := \033[0;32m
YELLOW := \033[0;33m
NC     := \033[0m

help: ## Show this help message
	@echo '${GREEN}Available commands:${NC}'
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  ${YELLOW}%-20s${NC} %s\n", $$1, $$2}'

install: ## Install dependencies
	@echo '${GREEN}Installing dependencies...${NC}'
	composer install

setup: install ## Setup the application
	@echo '${GREEN}Setting up application...${NC}'
	cp -n .env.example .env || true
	php artisan key:generate
	php artisan storage:link
	@echo '${GREEN}Setup complete!${NC}'
	@echo '${YELLOW}Don't forget to:${NC}'
	@echo '  1. Configure database in .env'
	@echo '  2. Run: make migrate'

migrate: ## Run database migrations
	@echo '${GREEN}Running migrations...${NC}'
	php artisan migrate

migrate-fresh: ## Fresh migrations with seeding
	@echo '${YELLOW}Warning: This will drop all tables!${NC}'
	php artisan migrate:fresh --seed

dev: ## Start development server
	@echo '${GREEN}Starting development server on http://localhost:8000${NC}'
	php artisan serve

test: ## Run tests
	@echo '${GREEN}Running tests...${NC}'
	php artisan test

test-coverage: ## Run tests with coverage
	@echo '${GREEN}Running tests with coverage...${NC}'
	php artisan test --coverage

lint: ## Check code style
	@echo '${GREEN}Checking code style...${NC}'
	./vendor/bin/pint --test

fix: ## Fix code style
	@echo '${GREEN}Fixing code style...${NC}'
	./vendor/bin/pint

clean: ## Clean cache and generated files
	@echo '${GREEN}Cleaning...${NC}'
	php artisan cache:clear
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear
	rm -rf bootstrap/cache/*.php

optimize: ## Optimize for production
	@echo '${GREEN}Optimizing...${NC}'
	composer install --optimize-autoloader --no-dev
	php artisan config:cache
	php artisan route:cache
	php artisan view:cache

queue: ## Start queue worker
	@echo '${GREEN}Starting queue worker...${NC}'
	php artisan queue:work

db-seed: ## Seed database
	@echo '${GREEN}Seeding database...${NC}'
	php artisan db:seed
