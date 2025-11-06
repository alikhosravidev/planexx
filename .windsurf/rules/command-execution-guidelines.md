---
trigger: always_on
---

# Docker Environment Rule

## Project Environment
This project runs entirely on Docker. All PHP, Laravel, Composer, and test commands MUST be executed inside Docker containers.

## Command Execution Guidelines

### ✅ Run Directly (Local)
- File system operations: `mkdir`, `touch`, `rm`, `mv`, `cp`
- Git operations: `git add`, `git commit`, `git push`
- Text editing and file modifications

### 🐳 Run via Docker
- **PHP commands**: `docker exec planexx_app php [command]`
- **Composer**: `docker exec planexx_app composer [command]`
- **Artisan**: `docker exec planexx_app php artisan [command]`
- **Tests**: `docker exec planexx_app php artisan test`
- **Any script execution**: `docker exec planexx_app [command]`

## Quick Reference
```bash
# ❌ Wrong
php artisan migrate
composer install

# ✅ Correct
docker exec planexx_app php artisan migrate
docker exec planexx_app composer install
```
