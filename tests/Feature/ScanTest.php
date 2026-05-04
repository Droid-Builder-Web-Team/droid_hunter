<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use App\Models\DroidScan;

class ScanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.core_portal.url', 'https://portal.test');
        Config::set('services.core_portal.secret', 'test_secret');
    }

    /** @test */
    public function a_valid_scan_signature_is_accepted_and_synced()
    {
        Http::fake([
            'portal.test/*' => Http::response(['success' => true], 200),
        ]);

        $droidId = 1;
        $signature = hash_hmac('sha256', $droidId, 'test_secret');

        $response = $this->get("/scan/{$droidId}?signature={$signature}");

        $response->assertRedirect();
        $this->assertDatabaseHas('droid_scans', [
            'droid_id' => $droidId,
        ]);

        // Verify that the portal was notified
        Http::assertSent(function ($request) use ($droidId) {
            return $request->url() == "https://portal.test/api/v1/droids/{$droidId}/scan" &&
                   $request->header('X-Hunter-Secret')[0] == 'test_secret';
        });
    }

    /** @test */
    public function an_invalid_scan_signature_is_rejected()
    {
        $response = $this->get("/scan/1?signature=wrong_sig");

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Invalid scan signature. Encounter rejected.');
        $this->assertDatabaseCount('droid_scans', 0);
    }
}
