#!/bin/bash

cat << 'EOF' > .git/hooks/pre-commit
#!/bin/sh

echo "🔧 Running pre-commit hook..."

# Configure safe directory for git
git config --local --replace-all safe.directory /var/www

# Load container name from .env
CONTAINER_NAME=$(grep -E '^CONTAINER_NAME=' .env | cut -d '=' -f2 | tr -d '\r"')
CONTAINER_NAME=${CONTAINER_NAME:-lsp}

# Export container name for scripts
export CONTAINER_NAME

# Get the working directory
WORKING_DIR="$(git rev-parse --show-toplevel)"

# Run check-imports script
echo ""
echo "📋 Step 1/2: Checking imports..."
sh ./standards/scripts/check-imports.sh || {
    echo "❌ Import check failed!"
    exit 1
}

# Run pint formatter script
echo ""
echo "🎨 Step 2/2: Running Laravel Pint formatter..."
sh ./standards/scripts/pint.sh || {
    echo "❌ Pint formatter failed!"
    exit 1
}

echo ""
echo "✅ Pre-commit hook completed successfully."

EOF

chmod +x .git/hooks/pre-commit

echo "✅ Pre-commit hook installed."
