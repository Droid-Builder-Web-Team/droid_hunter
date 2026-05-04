<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            function ($event) {
                $visitorId = request()->cookie('visitor_id') ?? session('visitor_id');
                \Log::info("Login Sync Attempt", ['user' => $event->user->id, 'visitor_id' => $visitorId]);
                if ($visitorId) {
                    $count = \App\Models\DroidScan::where('visitor_id', $visitorId)
                        ->whereNull('user_id')
                        ->update(['user_id' => $event->user->id]);
                    \Log::info("Login Sync Complete", ['synced_count' => $count]);
                }
            }
        );
    }
}