---
trigger: manual
---

# Test Execution Guide

## Run All Tests
```bash
docker exec -it lsp_app vendor/bin/phpunit --stop-on-failure --stop-on-error
```

## Run Specific Test File
```bash
docker exec -it lsp_app vendor/bin/phpunit tests/Feature/ExampleTest.php --stop-on-failure --stop-on-error
```

## Run Multiple Test Files
```bash
docker exec -it lsp_app vendor/bin/phpunit tests/Feature/Test1.php tests/Unit/Test2.php --stop-on-failure --stop-on-error
```

## Run Specific Test Method
```bash
docker exec -it lsp_app vendor/bin/phpunit --filter test_method_name --stop-on-failure --stop-on-error
```

---

### Persistent Failure Protocol
If a test still fails after a maximum of **three (3) attempts** to fix it, the debugging and fixing process must be **halted**. At this point, the developer is required to take the following steps:

1.  **Prepare a Detailed Report:** Stop the process and write a comprehensive report. **This report must be written in Persian (فارسی)** and should include:
    *   **Full Error Description (شرح کامل خطا):** A complete description of the error you are facing.
    *   **Root Cause Analysis (علت احتمالی):** Your analysis of the potential root cause(s).
    *   **Hypotheses (فرضیه‌ها):** A list of your hypotheses regarding the origin of the problem.
    *   **Suggested Solutions (پیشنهادها):** Your proposed solutions or next steps to resolve the issue.

2.  **Stop Execution:** Halt the entire test suite execution and escalate the prepared report to the project lead.

---

### Important Notes:
- ✅ Always use `--stop-on-failure --stop-on-error` flags.
- ✅ Test paths are relative to the project root.
- ✅ The container (`lsp_app`) must be running.
