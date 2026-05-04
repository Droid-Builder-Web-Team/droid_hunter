# Droid Hunter PWA

A decoupled Progressive Web App (PWA) for event attendees to collect and register scanned droids. Built for the Droid Builders UK community.

## 🚀 Key Features

- **Persistent Guest Mode**: Start hunting instantly. Progress is saved to the device (Sticky Guest) even without an account.
- **The Great Sync**: Anonymous progress is automatically claimed and merged when a user registers or logs in.
- **Active Member Filter**: The registry automatically filters out droids whose owners haven't attended an event in the last 2 years.
- **Rich Droid Specs**: View detailed weight, speed, material, and origin (Country/County) for every droid.
- **Encounter History**: Track every time you've spotted a droid with a full chronological log of your journey.
- **Detailed Chronicles**: Dive into a specific droid's profile to see all the dates and times you've encountered that particular unit.
- **Hunters Vault (Auth)**:
    - **Local Accounts**: Independent Email/Password registration.
    - **Data Links**: Social login support (Google/Apple).
    - **MFA Ready**: Built-in Multi-Factor Authentication support for maximum security.
- **PWA Ready**: Installable on iOS and Android with offline support and custom branding.

## 🛡️ Privacy & Security

- **Minimal Data Collection**: We only collect the bare minimum (Email/Nickname) required for account syncing.
- **No Marketing/Tracking**: No personal data is shared with third parties or used for marketing.
- **GDPR Compliant**: Users have full control over their data, including a "Delete Everything" option.
- **Signed Scans**: Uses HMAC-signed redirects from the Core Portal to ensure every "Spot" is authentic.

## 🛠️ Installation

1. **Clone & Install:**
   ```bash
   composer install
   npm install
   ```

2. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database & Auth:**
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

4. **Portal Integration:**
   Set the shared secret and portal URL in your `.env`:
   ```env
   HUNTER_SHARED_SECRET=your_secure_random_string
   CORE_PORTAL_URL=https://portal.droidbuilders.uk
   ```

5. **Build Assets:**
   ```bash
   npm run build
   ```

## 📜 License

This project is licensed under the **GNU General Public License v2.0 (GPL-2.0)**.
