# Droid Hunter & Core Portal: Engagement Loop Architecture

This document explains the synchronization and gamification bridge between the **Droid Hunter PWA** and the **Core Portal (db_mot)**.

## 🔄 The Data Loop

The integration creates a "closed loop" of engagement that rewards builders for bringing their droids to events.

1. **The Discovery (Scan)**:
   - A user scans a droid's QR code or NFC tag.
   - The Core Portal redirects the user to the Hunter App with an HMAC-signed URL.
   - The Hunter App verifies the signature and records the encounter locally.
   - **Sync**: The Hunter App sends a background `POST` request to the Portal's `/api/v1/droids/{id}/scan` endpoint.
   - **Result**: The droid's global `scan_count` is incremented on the Portal.

2. **The Recognition (Commendation)**:
   - After viewing a droid, the user can click **"COMMEND_BUILDER"**.
   - The Hunter App records this interaction (limited to 1 per droid/user per day).
   - **Sync**: The Hunter App sends a background `POST` request to the Portal's `/api/v1/droids/{id}/commend` endpoint.
   - **Result**: The builder's `commendations` count is incremented on the Portal.

## 🛡️ Security Model

### Shared Secret (HMAC)
Both applications share a `HUNTER_SHARED_SECRET`.
- **Portal to Hunter**: Every redirect URL is signed using `hash_hmac('sha256', droid_id, secret)`. This prevents users from "spoofing" scans by simply guessing IDs.
- **Hunter to Portal**: Every API call includes an `X-Hunter-Secret` header. The Portal validates this against its own config before allowing increments.

### CORS & Image Proxy
To allow the Hunter App to generate "Capture Cards" for social sharing, it must bypass S3's CORS restrictions:
1. Hunter App requests an image through its own `/proxy/image` route.
2. The Proxy fetches the image from the Portal using the shared secret.
3. The Proxy serves the raw image data with a permissive `Access-Control-Allow-Origin: *` header.
4. `html2canvas` can then safely render the image into a shareable PNG.

## 🛠️ Database Schema Changes

### Droid Hunter
- `droid_commendations`: (id, user_id, visitor_id, droid_id, created_at)
- `droid_scans`: (id, user_id, visitor_id, droid_id, event_name, created_at)

### Core Portal (db_mot)
- `droids.commendations`: (Integer, Default 0)
- `droids.scan_count`: (Integer, Default 0)

## 🧪 Testing the Loop
To verify the sync is working, check the Hunter App's `storage/logs/laravel.log`. Successful syncs will show:
`Portal Sync Success (Scan) for Droid 123`
`Portal Sync Success (Commend) for Droid 123`
