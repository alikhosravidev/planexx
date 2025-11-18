# Quick Start Guide

## Development Commands

### Docker Environment
⚠️ **IMPORTANT**: All PHP/Laravel commands MUST run inside Docker:
```bash
docker exec planexx_app php artisan [command]
docker exec planexx_app composer [command]
```

### Setup & Installation
```bash
composer setup          # Full setup
composer install        # Install dependencies
npm install            # Install JS dependencies
```

### Development Server
```bash
composer dev           # Start all services: server, queue, logs, vite
```

### Testing
```bash
composer test                              # Run all tests
docker exec planexx_app php artisan test  # Run test suite
./standards/scripts/parallel.sh            # Parallel testing
```

### Code Quality
```bash
./standards/scripts/pint.sh              # Format code
./standards/scripts/check-imports.sh     # Check unused imports
```

## Where to Find More

- **Architecture**: See `.claude/architecture.md`
- **Domain Layer**: See `.claude/domain/`
- **API Design**: See `.claude/api/`
- **Testing**: See `.claude/testing/`
- **Code Standards**: See `.claude/patterns/`

## Quick Reference

### Creating New Module
1. Read: `.claude/architecture.md`
2. Follow structure in CLAUDE.md
3. Use existing Core modules as reference

### Adding API Endpoint
1. Read: `.claude/api/design.md`
2. Create Controller in module
3. Define routes in module Routes/
4. Create Transformer

### Writing Tests
1. Read: `.claude/testing/guide.md`
2. Follow test organization by type
3. Use factories for test data
