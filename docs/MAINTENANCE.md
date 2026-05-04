# Maintenance & Testing Guide

This guide covers how to maintain the health and security of the Droid Hunter integration.

## 🧪 Running Tests

A feature test suite has been added to ensure the HMAC signature and sync logic remain functional.

### Prerequisites
- PHPUnit (included with Laravel)
- SQLite (for the in-memory test database)

### How to Run
Run the following command in the `droid_hunter` directory:
```bash
php artisan test
```

### Key Test Scenarios
- **Valid Signature**: Ensures valid HMAC tokens allow the user to record a scan.
- **Invalid Signature**: Ensures tampered or missing tokens result in a rejection.
- **Portal Sync**: Verifies that the Hunter app correctly sends the `POST` request to the Core Portal.

## 🛡️ Security Scanning (CodeQL)

The project includes a GitHub Action in `.github/workflows/codeql.yml`.

### What it does:
- Scans every `push` and `pull_request` to the `master` branch.
- **Languages**: Currently scans **JavaScript** (including PWA logic and Capture Card rendering).
- **Note**: CodeQL does not currently support PHP. For PHP security scanning, consider tools like **Enlightn** (Laravel specific) or **Psalm**.
- Checks for:
  - Hardcoded secrets/keys.
  - SQL Injection vulnerabilities (JS side).
  - Cross-site Scripting (XSS) in frontend logic.
  - Insecure logic patterns.

### How to view results:
If you host this project on GitHub, results will appear under the **Security** tab of the repository.

## 🔄 Updating the Shared Secret

If you need to rotate the `HUNTER_SHARED_SECRET`:
1. Generate a new random string (e.g., using `php artisan key:generate --show`).
2. Update the `.env` in the **Core Portal**.
3. Update the `.env` in the **Droid Hunter App**.
4. Clear config caches on both apps:
   ```bash
   php artisan config:clear
   ```
