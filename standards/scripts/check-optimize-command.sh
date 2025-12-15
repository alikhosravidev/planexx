#!/bin/sh

set -e

echo ""
echo "🔧 Checking Laravel optimize commands..."

# Load container name from environment or .env (falls back to 'planexx')
if [ -z "$CONTAINER_NAME" ]; then
    CONTAINER_NAME=$(grep -E '^CONTAINER_NAME=' .env 2>/dev/null | cut -d '=' -f2 | tr -d '\r"')
    CONTAINER_NAME=${CONTAINER_NAME:-planexx}
fi

APP_CONTAINER="${CONTAINER_NAME}_app"
EXIT_CODE=0

if command -v docker >/dev/null 2>&1; then
    echo "🐳 Trying inside Docker container: $APP_CONTAINER"

    docker exec "$APP_CONTAINER" php artisan optimize || {
        echo ""
        echo "❌ Failed to run 'php artisan optimize' in Docker container."
        exit 1
    }

    docker exec "$APP_CONTAINER" php artisan optimize:clear || {
        echo ""
        echo "❌ Failed to run 'php artisan optimize:clear' in Docker container."
        exit 1
    }
else
    echo ""
    echo "⚠️  Docker is unavailable. Trying to run commands locally..."

    php artisan optimize || {
        echo "❌ Failed to run 'php artisan optimize'."
        exit 1
    }

    php artisan optimize:clear || {
        echo "❌ Failed to run 'php artisan optimize:clear'."
        exit 1
    }
fi

echo ""
echo "✅ Laravel optimize commands executed successfully."
