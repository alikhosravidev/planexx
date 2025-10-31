#!/bin/bash

echo "🔧 Installing Git Hooks..."

# Configure safe directory for git
git config --local --replace-all safe.directory /var/www

# Install pre-commit hook
sh ./standards/hooks/init-pre-commit.sh

# Install pre-push hook
sh ./standards/hooks/init-pre-push.sh

echo "✅ Git Hooks installed successfully."
