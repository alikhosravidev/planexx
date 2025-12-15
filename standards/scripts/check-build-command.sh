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

if command -v docker >/dev/null 2>&1; then
    echo "🐳 Trying npm run build inside Docker container: $NODE_CONTAINER"

    docker exec "$NODE_CONTAINER" npm run build || {
        echo ""
        echo "⚠️  Docker execution failed. Trying to run 'npm run build' locally..."
        npm run build || {
            echo "❌ Failed to run 'npm run build'."
            exit 1
        }
    }
else
    echo ""
    echo "⚠️  Docker is unavailable. Trying to run 'npm run build' locally..."
    npm run build || {
        echo "❌ Failed to run 'npm run build'."
        exit 1
    }
fi

echo ""
echo "✅ Frontend build (npm run build) executed successfully."
