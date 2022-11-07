#!/bin/sh

composer install
cp composer.lock vendor/orchestra/testbench-core/compser.lock
php ./vendor/bin/testbench package:discover --ansi
