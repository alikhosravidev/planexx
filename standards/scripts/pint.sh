#!/bin/sh

set -e

echo "🔧 Running Laravel Pint on STAGED files..."

# Configure safe directory for git
git config --local --replace-all safe.directory /var/www 2>/dev/null || true

# Load container name from environment or .env
if [ -z "$CONTAINER_NAME" ]; then
    CONTAINER_NAME=$(grep -E '^CONTAINER_NAME=' .env 2>/dev/null | cut -d '=' -f2 | tr -d '\r"')
    CONTAINER_NAME=${CONTAINER_NAME:-planexx}
fi

# Get staged PHP files
FILES_TO_PINT=$(git diff --name-only --cached --diff-filter=ACM 2>/dev/null | grep '\.php$' || true)

# Check if there are any PHP files to format
if [ -z "$FILES_TO_PINT" ]; then
    echo "✅ No staged PHP files to format. Skipping."
    exit 0
fi

echo "📁 Found staged PHP files:"
echo "$FILES_TO_PINT" | sed 's/^/  - /'

# Pint configuration file path
PINT_CONFIG="./standards/formatter/pint.json"

# Check if pint config exists
if [ ! -f "$PINT_CONFIG" ]; then
    echo "⚠️  Pint config not found at: $PINT_CONFIG"
    echo "Using default Pint configuration..."
    PINT_CONFIG=""
else
    PINT_CONFIG="--config $PINT_CONFIG"
fi

echo ""
echo "🎨 Formatting files with Laravel Pint..."

# Try to run in Docker first
if command -v docker >/dev/null 2>&1; then
    docker exec "${CONTAINER_NAME}_app" chmod +x vendor/bin/pint 2>/dev/null || true
    if [ -n "$PINT_CONFIG" ]; then
        if ! docker exec "${CONTAINER_NAME}_app" bash -c "echo '$FILES_TO_PINT' | xargs -r ./vendor/bin/pint $PINT_CONFIG"; then
            echo "❌ Pint formatting failed!"
            exit 1
        fi
    else
        if ! docker exec "${CONTAINER_NAME}_app" bash -c "echo '$FILES_TO_PINT' | xargs -r ./vendor/bin/pint"; then
            echo "❌ Pint formatting failed!"
            exit 1
        fi
    fi
else
    chmod +x vendor/bin/pint 2>/dev/null || true
    if [ -n "$PINT_CONFIG" ]; then
        if ! echo "$FILES_TO_PINT" | xargs -r ./vendor/bin/pint $PINT_CONFIG; then
            echo "❌ Pint formatting failed!"
            exit 1
        fi
    else
        if ! echo "$FILES_TO_PINT" | xargs -r ./vendor/bin/pint; then
            echo "❌ Pint formatting failed!"
            exit 1
        fi
    fi
fi

# Re-add the formatted files to staging area
echo ""
echo "📝 Re-staging formatted files..."
echo "$FILES_TO_PINT" | xargs -r git add

echo ""
echo "✅ Pint formatting finished successfully."
