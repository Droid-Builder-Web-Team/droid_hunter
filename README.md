# Droid Hunter PWA

A decoupled Progressive Web App (PWA) for event attendees to collect and register scanned droids. Built for the Droid Builders UK community.

## Overview

Droid Hunter allows users to "collect" physical droids at events by scanning QR codes or NFC tags. It integrates securely with the [Core Portal](https://portal.droidbuilders.uk/) to validate scans and provide droid data.

### Key Features
- **PWA Ready**: Installable on iOS and Android with offline support.
- **Friction-free Onboarding**: "Quick Start" login with just a nickname.
- **Secure Scans**: Uses HMAC-signed redirects from the Core Portal to prevent spoofing.
- **Droid Registry**: A premium, dark-themed collection grid with club-based silhouettes for uncollected droids.

## Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite (default) or MySQL

## Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd droid_hunter
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup:**
   Copy the example environment file and generate an application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration:**
   Create an empty SQLite database:
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

5. **Shared Secret Configuration:**
   For the secure scan system to work, you must set the same shared secret in both this app and the Core Portal:
   ```env
   HUNTER_SHARED_SECRET=your_secure_random_string
   CORE_PORTAL_URL=https://portal.droidbuilders.uk
   ```

6. **Build Assets:**
   ```bash
   npm run build
   ```

## Development

Run the local development server:
```bash
php artisan serve
```

For PWA testing on mobile devices, use the `--host` flag:
```bash
php artisan serve --host=0.0.0.0
```

## Security

Scans are verified using `hash_hmac('sha256', ...)`. The physical tags should point to the Core Portal redirector (e.g., `https://portal.droidbuilders.uk/scan/{id}`), which will then redirect back to the PWA with a signature.

## License

This project is licensed under the **GNU General Public License v2.0 (GPL-2.0)**. See the `LICENSE` file for details.
