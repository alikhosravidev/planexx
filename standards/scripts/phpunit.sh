#!/bin/sh

# Immediately exit the script if any command fails.
set -e

echo "🔧 Running phpunit tests..."

# Track script start time and ensure duration is printed on both success and failure
START_TIME=$(date +%s)

print_duration() {
    NOW=$(date +%s)
    ELAPSED=$((NOW - START_TIME))
    H=$((ELAPSED / 3600))
    M=$(((ELAPSED % 3600) / 60))
    S=$((ELAPSED % 60))
    printf "⏱ Duration: %02d:%02d:%02d\n" "$H" "$M" "$S"
}

trap 'print_duration' EXIT

# Configure safe directory for git
git config --local --replace-all safe.directory /var/www 2>/dev/null || true

# Load container name from environment or .env.testing
if [ -z "$CONTAINER_NAME" ]; then
    CONTAINER_NAME=$(grep -E '^CONTAINER_NAME=' .env.testing 2>/dev/null | cut -d '=' -f2 | tr -d '\r"')
    if [ -z "$CONTAINER_NAME" ]; then
        CONTAINER_NAME=$(grep -E '^CONTAINER_NAME=' .env 2>/dev/null | cut -d '=' -f2 | tr -d '\r"')
    fi
    CONTAINER_NAME=${CONTAINER_NAME:-lsp}
fi

# Load database name from .env.testing
DB_DATABASE=$(grep -E '^DB_DATABASE=' .env.testing 2>/dev/null | cut -d '=' -f2 | tr -d '\r"')
DB_DATABASE=${DB_DATABASE:-lsp_test}

echo "⚙️  Configuration:"
echo "  - Container: ${CONTAINER_NAME}_app"
echo "  - Database: ${DB_DATABASE}"
echo ""

# Find all 'Tests' directories within the 'app' and 'Modules' directories
if [ -n "$1" ]; then
    if [ ! -d "$1" ]; then
        echo "❌ Directory '$1' does not exist!"
        exit 1
    fi
    TEST_DIRS=$(find "$1" -type d -name "Tests" 2>/dev/null || true)
else
    TEST_DIRS=$(find ./app -type d -name "Tests" 2>/dev/null || true)
fi

if [ -z "$TEST_DIRS" ]; then
    echo "⚠️  No test directories found!"
    exit 0
fi

echo "📁 Found test directories:"
echo "$TEST_DIRS" | sed 's/^/  - /'
echo ""

# Track if any test failed
OVERALL_EXIT_CODE=0

# Iterate over test directories
echo "$TEST_DIRS" | while read -r test_dir; do
    if [ -z "$test_dir" ]; then
        continue
    fi

    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo "🧪 Running tests in: ${test_dir}"
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

    # Try to run in Docker first
    if command -v docker >/dev/null 2>&1; then
        if ! docker exec "${CONTAINER_NAME}_app" vendor/bin/phpunit --configuration ./phpunit.xml "${test_dir}"; then
            echo "❌ Tests failed in: ${test_dir}"
            exit 1
        fi
    else
        if ! vendor/bin/phpunit --configuration ./phpunit.xml "${test_dir}"; then
            echo "❌ Tests failed in: ${test_dir}"
            exit 1
        fi
    fi

    echo ""
    echo "✅ Tests passed in: ${test_dir}"
    echo ""
done

# Check if the loop failed
if [ $? -ne 0 ]; then
    echo ""
    echo "❌ Some tests failed!"
    exit 1
fi

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ All test directories processed successfully!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
