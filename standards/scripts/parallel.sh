#!/bin/sh

# Immediately exit the script if any command fails.
set -e

echo "🔧 Running parallel tests..."

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

# Load parallel processes count from .env.testing
PARALLEL_PROCESSES_COUNT=$(grep -E '^PARALLEL_PROCESSES_COUNT=' .env.testing 2>/dev/null | cut -d '=' -f2 | tr -d '\r"')
PARALLEL_PROCESSES_COUNT=${PARALLEL_PROCESSES_COUNT:-4}

echo "⚙️  Configuration:"
echo "  - Container: ${CONTAINER_NAME}_app"
echo "  - Database: ${DB_DATABASE}"
echo "  - Parallel Processes: ${PARALLEL_PROCESSES_COUNT}"
echo ""

# Find all 'Tests' directories within the 'app' and 'Modules' directories
TEST_DIRS=$(find ./app ./Modules -type d -name "Tests" 2>/dev/null || true)

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
    EXIT_CODE=0
    docker exec "${CONTAINER_NAME}_app" env DB_DATABASE="${DB_DATABASE}" vendor/bin/paratest -p "${PARALLEL_PROCESSES_COUNT}" --configuration ./phpunit.xml "${test_dir}" 2>/dev/null || EXIT_CODE=$?
    
    if [ "$EXIT_CODE" -ne 0 ]; then
        echo ""
        echo "⚠️  Docker execution failed. Trying locally..."
        DB_DATABASE="${DB_DATABASE}" vendor/bin/paratest -p "${PARALLEL_PROCESSES_COUNT}" --configuration ./phpunit.xml "${test_dir}" || {
            echo ""
            echo "❌ Tests failed in: ${test_dir}"
            exit 1
        }
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
