#!/bin/bash

set -e

cd "$(dirname "$0")"

BIN=node_modules/.bin

npm-cache install npm

$BIN/gulp --production

uploads=../assets/uploads
if [ -L $uploads ]; then
    echo $uploads already exists!
else
    ln -s ../protected/storage/app/public/ $uploads
fi
