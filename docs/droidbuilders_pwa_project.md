# Droid Builders UK: PWA Collection System Project

## Context
- **Industry:** Robotics / Hobbyist / Conventions
- **Primary Tech:** Laravel (PHP), NFC/QR, PWA
- **Goal:** Gamify droid-spotting for public visitors via a secure, decoupled PWA.

## Technical Architecture

### 1. Decoupled Systems
- **Core Portal:** (Existing) Laravel app holding sensitive member data.
- **Hunter PWA:** (New) Separate Laravel instance for public scanning and collections.
- **API Bridge:** Core Portal provides public droid data via Sanctum-authenticated API.

### 2. Scanning Mechanics
- **Hardware:** NTAG213 (Anti-metal for droid shells).
- **Security:** Laravel Signed URLs to prevent manual ID incrementing.
- **Routing:** Centralized redirector on the core portal to allow for future URL changes.

### 3. User Experience
- **Platform:** PWA (Installable to Home Screen).
- **Onboarding:** Socialite (Google/Apple login) to minimize friction.
- **Features:** Digital registry, "Found" badges, rarity tiers.

## Development Checklist
- [ ] Implement API endpoints on Core Portal for public droid info.
- [ ] Set up new Laravel instance for Hunter PWA.
- [ ] Configure Signed URL redirection logic.
- [ ] Integrate NTAG213 hardware testing.
- [ ] Implement PWA manifest and Service Workers.
