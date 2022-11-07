#!/bin/sh

composer install
cp composer.lock vendor/orchestra/testbench-core/laravel/composer.lock
php ./vendor/bin/testbench package:discover --ansi
