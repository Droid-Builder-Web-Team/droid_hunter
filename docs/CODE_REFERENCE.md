# Droid Hunter & Core Portal: Code Reference

This document provides a technical overview of the functions, classes, and logic implemented for the Droid Hunter integration.

## 🏹 Droid Hunter (Client App)

### Controllers

#### `App\Http\Controllers\RegistryController`
- **`proxyImage(Request $request, $id, $photo_name)`**: 
  - **Purpose**: Relays droid images from the Portal to the PWA while injecting the shared secret.
  - **Security**: Ensures images are only served if the requester provides a valid `X-Hunter-Secret`.
  - **CORS**: Strips S3 redirects and serves raw data with `Access-Control-Allow-Origin: *` to support `html2canvas`.
- **`commendDroid(Request $request, $id)`**:
  - **Purpose**: Records a community "Commendation" for a builder.
  - **Logic**: Prevents duplicates via local DB check, then triggers an async sync to the Core Portal.

#### `App\Http\Controllers\ScanController`
- **`process(Request $request, $id)`**:
  - **Purpose**: Handles incoming scans from the Portal.
  - **Security**: Validates the `signature` query parameter using HMAC-SHA256.
  - **Sync**: Independently notifies the Portal of the scan event to increment global discovery stats.

### Models
- **`App\Models\DroidCommendation`**: Tracks builder recognition interactions.
- **`App\Models\DroidScan`**: Tracks individual user encounters with droids.

---

## 🏛️ Core Portal (Master Registry)

### API Controllers

#### `App\Http\Controllers\Api\V1\DroidController`
- **`index()`**: Returns a paginated list of all public droids.
- **`show($id)`**: Returns detailed JSON for a specific droid.
- **`commend(Request $request, $id)`**: 
  - **Endpoint**: `POST /api/v1/droids/{id}/commend`
  - **Security**: Requires `X-Hunter-Secret` header.
  - **Action**: Increments the `commendations` column (timestamps disabled).
- **`reportScan(Request $request, $id)`**:
  - **Endpoint**: `POST /api/v1/droids/{id}/scan`
  - **Security**: Requires `X-Hunter-Secret` header.
  - **Action**: Increments the `scan_count` column (timestamps disabled).

### Models

#### `App\User`
- **`totalCommendations()`**: 
  - **Return**: `int`
  - **Logic**: Sums the `commendations` column across all droids belonging to the user. Used for builder prestige on profile pages.

---

## 📡 Common Shared Logic

### HMAC Signature Generation
The Portal generates signatures for redirects using:
```php
$signature = hash_hmac('sha256', $droid_id, config('services.hunter_pwa.secret'));
```

The Hunter app validates these using `hash_equals()`.
