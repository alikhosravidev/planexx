#!/bin/sh

set -e

echo "🔧 Running JS Formatter..."

# Load container name from environment or .env
if [ -z "$CONTAINER_NAME" ]; then
    CONTAINER_NAME=$(grep -E '^CONTAINER_NAME=' .env 2>/dev/null | cut -d '=' -f2 | tr -d '\r"')
    CONTAINER_NAME=${CONTAINER_NAME:-planexx}
fi

if command -v docker >/dev/null 2>&1; then
    docker exec -it "${CONTAINER_NAME}_node" npm run format || {
        echo ""
        echo "⚠️  Docker execution failed. Trying locally..."
        npm run format || {
            echo "❌ JS formatting failed!"
            exit 1
        }
    }
else
    echo ""
    echo "⚠️  Docker is unavailable. Trying locally..."
    npm run format || {
        echo "❌ JS formatting failed!"
        exit 1
    }
fi

echo ""
echo "✅ JS formatting finished successfully."
