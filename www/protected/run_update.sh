#!/bin/bash

set -e

cd "$(dirname "$0")"

php artisan view:clear
php artisan cache:clear
