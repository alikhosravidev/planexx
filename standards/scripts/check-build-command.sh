#!/bin/sh

set -e

echo ""
echo "🔧 Checking frontend build (npm run build)..."

# Load container name from environment or .env (falls back to 'planexx')
if [ -z "$CONTAINER_NAME" ]; then
    CONTAINER_NAME=$(grep -E '^CONTAINER_NAME=' .env 2>/dev/null | cut -d '=' -f2 | tr -d '\r"')
    CONTAINER_NAME=${CONTAINER_NAME:-planexx}
fi

NODE_CONTAINER="${CONTAINER_NAME}_node"
EXIT_CODE=0

if command -v docker >/dev/null 2>&1; then
    echo "🐳 Trying npm run build inside Docker container: $NODE_CONTAINER"

    if ! docker exec "$NODE_CONTAINER" npm run build; then
        EXIT_CODE=$?
    fi
else
    EXIT_CODE=1
fi

if [ "$EXIT_CODE" -ne 0 ]; then
    echo ""
    echo "⚠️  Docker execution failed or Docker is unavailable. Trying to run 'npm run build' locally..."

    if ! npm run build; then
        echo "❌ Failed to run 'npm run build'."
        exit 1
    fi
fi

echo ""
echo "✅ Frontend build (npm run build) executed successfully."
