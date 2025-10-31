#!/bin/sh

set -e

echo "🔧 Running Check Imports on STAGED files..."

# Configure safe directory for git
git config --local --replace-all safe.directory /var/www 2>/dev/null || true

# Load container name from environment or .env
if [ -z "$CONTAINER_NAME" ]; then
    CONTAINER_NAME=$(grep -E '^CONTAINER_NAME=' .env 2>/dev/null | cut -d '=' -f2 | tr -d '\r"')
    CONTAINER_NAME=${CONTAINER_NAME:-lsp}
fi

# Get staged PHP files
STAGED_FILES=$(git diff --name-only --cached --diff-filter=ACM 2>/dev/null | grep '\.php$' || true)

if [ -z "$STAGED_FILES" ]; then
    echo "✅ No staged PHP files to check imports. Skipping."
    exit 0
fi

echo "📁 Found staged PHP files:"
echo "$STAGED_FILES" | sed 's/^/  - /'

# Extract unique directories from staged files
UNIQUE_DIRS=$(echo "$STAGED_FILES" | xargs -n 1 dirname | sort -u)

# Convert to comma-separated format
COMMA_SEPARATED_DIRS=$(echo "$UNIQUE_DIRS" | paste -sd,)

echo ""
echo "📂 Checking imports in directories: $COMMA_SEPARATED_DIRS"

# Try to run in Docker first
EXIT_CODE=0
docker exec "${CONTAINER_NAME}_app" php artisan check:imports --folder="$COMMA_SEPARATED_DIRS" 2>/dev/null || EXIT_CODE=$?

if [ "$EXIT_CODE" -ne 0 ]; then
    echo ""
    echo "⚠️  Docker execution failed. Trying locally..."
    php artisan check:imports --folder="$COMMA_SEPARATED_DIRS" || {
        echo ""
        echo "❌ Import check failed!"
        exit 1
    }
fi

echo ""
echo "✅ Import check finished successfully."
