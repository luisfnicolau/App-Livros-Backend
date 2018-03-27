help:
	@echo "\t install para instalar no começo do projeto"
	@echo "\t run para rodar em localhost:8000"
	@echo "\t clear para limpar cache e configurações do artisan"

install: composer.json
	composer install
	@echo "\t now change databse access in you .env file"
	@echo "\t And then, run make post-install"

db: composer.json .env
	php artisan migrate
	php artisan db:seed

post-install:
	make db

run: composer.json vendor/
	make db
	php artisan queue:work&
	php artisan serve

reset-db:
	php artisan users:remove
	php artisan migrate:reset
	make db

clear: composer.json
	php artisan clear-compiled
	php artisan cache:clear
	php artisan route:clear
	php artisan view:clear
	php artisan auth:clear-resets
