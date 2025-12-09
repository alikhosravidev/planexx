#!/bin/sh

set -e

echo "🔧 Running JS Formatter..."

# Load container name from environment or .env
if [ -z "$CONTAINER_NAME" ]; then
    CONTAINER_NAME=$(grep -E '^CONTAINER_NAME=' .env 2>/dev/null | cut -d '=' -f2 | tr -d '\r"')
    CONTAINER_NAME=${CONTAINER_NAME:-planexx}
fi

EXIT_CODE=0

if command -v docker >/dev/null 2>&1; then
    docker exec -it "${CONTAINER_NAME}_node" npm run format 2>/dev/null || EXIT_CODE=$?
else
    EXIT_CODE=1
fi

if [ "$EXIT_CODE" -ne 0 ]; then
    echo ""
    echo "⚠️  Docker execution failed or Docker is unavailable. Trying locally..."
    if ! npm run format; then
        echo "❌ JS formatting failed!"
        exit 1
    fi
fi

echo ""
echo "✅ JS formatting finished successfully."
