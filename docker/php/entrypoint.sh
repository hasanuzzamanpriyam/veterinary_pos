#!/bin/sh

set -e

# Create storage symlink if it doesn't exist
if [ ! -L public/storage ]; then
    php artisan storage:link
fi

# Run migrations (optional)
# php artisan migrate --force

exec "$@"
