.PHONY: up down bash migrate fresh install

up:
	docker compose up -d --build

down:
	docker compose down

bash:
	docker compose exec app bash

install:
	docker compose exec app composer install
	docker compose exec app npm ci --legacy-peer-deps

migrate:
	docker compose exec app php artisan migrate --force

fresh:
	docker compose exec app php artisan migrate:fresh --seed --force
