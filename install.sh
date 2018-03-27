#!/bin/bash
composer install
composer update
cp example.env .env
composer run-script post-create-project-cmd
composer run-script post-install-cmd
composer run-script post-update-cmd
php artisan migrate
