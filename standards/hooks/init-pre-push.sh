#!/bin/bash

cat << 'EOF' > .git/hooks/pre-push
#!/bin/sh

echo "🚀 Running pre-push hook..."

# Configure safe directory for git
git config --local --replace-all safe.directory /var/www

# Load container name from .env
CONTAINER_NAME=$(grep -E '^CONTAINER_NAME=' .env | cut -d '=' -f2 | tr -d '\r"')
CONTAINER_NAME=${CONTAINER_NAME:-planexx}

# Export container name for scripts
export CONTAINER_NAME

# Get the working directory
WORKING_DIR="$(git rev-parse --show-toplevel)"

# Run optimize checks
echo ""
echo "🧹 Running Laravel optimize checks..."
sh ./standards/scripts/check-optimize-command.sh || {
    echo "❌ Laravel optimize checks failed!"
    exit 1
}

# Run fetch commands checks
echo ""
echo "� Running Laravel fetch commands checks..."
sh ./standards/scripts/check-fetch-commands.sh || {
    echo "❌ Laravel fetch commands checks failed!"
    exit 1
}

# Run migrations checks (rollback then migrate)
echo ""
echo "📦 Running Laravel migrations checks (rollback and migrate)..."
sh ./standards/scripts/check-migrate-command.sh || {
    echo "❌ Laravel migrations checks failed!"
    exit 1
}

# Run frontend build checks (npm run build)
echo ""
echo "🧱 Running frontend build checks (npm run build)..."
sh ./standards/scripts/check-build-command.sh || {
    echo "❌ Frontend build checks failed!"
    exit 1
}

# Run tests
echo ""
echo "🧪 Running tests..."
sh ./standards/scripts/parallel.sh || {
    echo "❌ Tests failed!"
    exit 1
}

echo ""
echo "✅ Pre-push hook completed successfully."

EOF

chmod +x .git/hooks/pre-push

echo "✅ Pre-push hook installed."
