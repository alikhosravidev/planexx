# Test Execution

> **Source**: `.windsurf/rules/test_execution_guidelines.md`

## Run All Tests
```bash
docker exec -it planexx_app php artisan test --stop-on-failure --stop-on-error
```

## Run Specific Test File
```bash
docker exec -it planexx_app php artisan test tests/Feature/ExampleTest.php --stop-on-failure --stop-on-error
```

## Run Multiple Test Files
```bash
docker exec -it planexx_app php artisan test tests/Feature/Test1.php tests/Unit/Test2.php --stop-on-failure --stop-on-error
```

## Run Specific Test Method
```bash
docker exec -it planexx_app php artisan test --filter test_method_name --stop-on-failure --stop-on-error
```

## Persistent Failure Protocol
If a test fails after **3 attempts**, STOP and prepare detailed report **in Persian**:

1. **شرح کامل خطا** (Full error description)
2. **علت احتمالی** (Root cause analysis)
3. **فرضیه‌ها** (Hypotheses)
4. **پیشنهادها** (Suggested solutions)

Then halt and escalate to project lead.

## Important Notes
- ✅ Always use `--stop-on-failure --stop-on-error`
- ✅ Test paths relative to project root
- ✅ Container must be running

## Full Details
`.windsurf/rules/test_execution_guidelines.md`

## Related
- Testing Guide: `.claude/testing/guide.md`
- Docker Commands: `.claude/guidelines/docker.md`
