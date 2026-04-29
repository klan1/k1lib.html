#!/bin/bash

set -e

PORT=${1:-8080}
DOCROOT="examples"

if [ ! -d "vendor" ]; then
    echo "vendor/ not found. Running composer install..."
    composer install
fi

echo "Starting PHP development server on http://localhost:$PORT"
echo "Examples index: http://localhost:$PORT/"
echo "Press Ctrl+C to stop."

php -S localhost:$PORT -t $DOCROOT
