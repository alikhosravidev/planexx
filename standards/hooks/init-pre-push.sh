#!/bin/bash

cat << 'EOF' > .git/hooks/pre-push
#!/bin/sh

echo "🚀 Running pre-push hook..."

# Configure safe directory for git
git config --local --replace-all safe.directory /var/www

# Load container name from .env
CONTAINER_NAME=$(grep -E '^CONTAINER_NAME=' .env | cut -d '=' -f2 | tr -d '\r"')
CONTAINER_NAME=${CONTAINER_NAME:-lsp}

# Export container name for scripts
export CONTAINER_NAME

# Get the working directory
WORKING_DIR="$(git rev-parse --show-toplevel)"

# Run parallel tests
echo ""
echo "🧪 Running parallel tests..."
sh ./standards/scripts/parallel.sh || {
    echo "❌ Tests failed!"
    exit 1
}

echo ""
echo "✅ Pre-push hook completed successfully."

EOF

chmod +x .git/hooks/pre-push

echo "✅ Pre-push hook installed."
