#!/bin/sh

set -e

echo ""
echo "🔧 Checking Laravel migrations (rollback then migrate)..."

# Load container name from environment or .env (falls back to 'planexx')
if [ -z "$CONTAINER_NAME" ]; then
    CONTAINER_NAME=$(grep -E '^CONTAINER_NAME=' .env 2>/dev/null | cut -d '=' -f2 | tr -d '\r"')
    CONTAINER_NAME=${CONTAINER_NAME:-planexx}
fi

APP_CONTAINER="${CONTAINER_NAME}_app"

if command -v docker >/dev/null 2>&1; then
    echo "🐳 Trying inside Docker container: $APP_CONTAINER"

    docker exec "$APP_CONTAINER" php artisan migrate:rollback || {
        echo ""
        echo "⚠️  Docker execution failed. Trying to run commands locally..."
        php artisan migrate:rollback || {
            echo "❌ Failed to run 'php artisan migrate:rollback'."
            exit 1
        }
    }

    docker exec "$APP_CONTAINER" php artisan migrate || {
        echo ""
        echo "⚠️  Docker execution failed. Trying to run commands locally..."
        php artisan migrate || {
            echo "❌ Failed to run 'php artisan migrate'."
            exit 1
        }
    }
else
    echo ""
    echo "⚠️  Docker is unavailable. Trying to run commands locally..."
    php artisan migrate:rollback || {
        echo "❌ Failed to run 'php artisan migrate:rollback'."
        exit 1
    }

    php artisan migrate || {
        echo "❌ Failed to run 'php artisan migrate'."
        exit 1
    }
fi

echo ""
echo "✅ Laravel migrations (rollback and migrate) executed successfully."
