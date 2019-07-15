#!/bin/bash

set -e

cd "$(dirname "$0")"

if test "$#" -ne 1; then
    ENV=qa_php7
else
    ENV=$1
fi

php artisan db:seed
php artisan db:seed --class=TestSeeder

vendor/bin/codecept run --xml --no-exit --env $ENV --no-colors
