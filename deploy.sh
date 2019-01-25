#!/bin/sh
# activate maintenance mode
php artisan down
# update source code
git pull
# update PHP dependencies
composer install --no-interaction
# --no-interaction Do not ask any interactive question
# --no-dev  Disables installation of require-dev packages.
# --prefer-dist  Forces installation from package dist even for dev versions.
# update database
php artisan migrate
#Install npm dependencies
npm install
#Run design updates
npm run dev
# --force  Required to run when in production.
# stop maintenance mode
php artisan up