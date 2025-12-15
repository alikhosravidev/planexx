#!/bin/sh

set -e

echo ""
echo "🔧 Checking Laravel fetch commands (events, entities, enums)..."

# Load container name from environment or .env (falls back to 'planexx')
if [ -z "$CONTAINER_NAME" ]; then
    CONTAINER_NAME=$(grep -E '^CONTAINER_NAME=' .env 2>/dev/null | cut -d '=' -f2 | tr -d '\r"')
    CONTAINER_NAME=${CONTAINER_NAME:-planexx}
fi

APP_CONTAINER="${CONTAINER_NAME}_app"

if command -v docker >/dev/null 2>&1; then
    echo "🐳 Trying inside Docker container: $APP_CONTAINER"

    docker exec "$APP_CONTAINER" php artisan fetch:events || {
        echo ""
        echo "⚠️  Docker execution failed. Trying to run commands locally..."
        php artisan fetch:events || {
            echo "❌ Failed to run 'php artisan fetch:events'."
            exit 1
        }
    }

    docker exec "$APP_CONTAINER" php artisan fetch:entities || {
        echo ""
        echo "⚠️  Docker execution failed. Trying to run commands locally..."
        php artisan fetch:entities || {
            echo "❌ Failed to run 'php artisan fetch:entities'."
            exit 1
        }
    }

    docker exec "$APP_CONTAINER" php artisan fetch:enums || {
        echo ""
        echo "⚠️  Docker execution failed. Trying to run commands locally..."
        php artisan fetch:enums || {
            echo "❌ Failed to run 'php artisan fetch:enums'."
            exit 1
        }
    }
else
    echo ""
    echo "⚠️  Docker is unavailable. Trying to run commands locally..."
    php artisan fetch:events || {
        echo "❌ Failed to run 'php artisan fetch:events'."
        exit 1
    }

    php artisan fetch:entities || {
        echo "❌ Failed to run 'php artisan fetch:entities'."
        exit 1
    }

    php artisan fetch:enums || {
        echo "❌ Failed to run 'php artisan fetch:enums'."
        exit 1
    }
fi

echo ""
echo "✅ Laravel fetch commands executed successfully."
