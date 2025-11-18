# Docker Environment

> **Source**: `.windsurf/rules/command-execution-guidelines.md`

## Project Environment
This project runs entirely on Docker. All PHP, Laravel, Composer, and test commands **MUST** run inside Docker containers.

## Command Execution Guidelines

### ✅ Run Directly (Local)
- File operations: `mkdir`, `touch`, `rm`, `mv`, `cp`
- Git operations: `git add`, `git commit`, `git push`
- Text editing and file modifications

### 🐳 Run via Docker
- **PHP**: `docker exec planexx_app php [command]`
- **Composer**: `docker exec planexx_app composer [command]`
- **Artisan**: `docker exec planexx_app php artisan [command]`
- **Tests**: `docker exec planexx_app php artisan test`
- **Any script**: `docker exec planexx_app [command]`

## Quick Reference
```bash
# ❌ Wrong
php artisan migrate
composer install

# ✅ Correct
docker exec planexx_app php artisan migrate
docker exec planexx_app composer install
```

## Full Details
`.windsurf/rules/command-execution-guidelines.md`

## Related
- Quick Start: `.claude/quick-start.md`
- Testing Execution: `.claude/testing/execution.md`
