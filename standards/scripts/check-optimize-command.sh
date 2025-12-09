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

    if ! docker exec "$APP_CONTAINER" php artisan optimize; then
        EXIT_CODE=$?
    fi

    if ! docker exec "$APP_CONTAINER" php artisan optimize:clear; then
        EXIT_CODE=$?
    fi
else
    EXIT_CODE=1
fi

if [ "$EXIT_CODE" -ne 0 ]; then
    echo ""
    echo "⚠️  Docker execution failed or Docker is unavailable. Trying to run commands locally..."

    if ! php artisan optimize; then
        echo "❌ Failed to run 'php artisan optimize'."
        exit 1
    fi

    if ! php artisan optimize:clear; then
        echo "❌ Failed to run 'php artisan optimize:clear'."
        exit 1
    fi
fi

echo ""
echo "✅ Laravel optimize commands executed successfully."
