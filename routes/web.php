<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('registry.index');
});

Route::get('/scan/{id}', [App\Http\Controllers\ScanController::class, 'process'])->name('scan.process');

Route::middleware(['auth'])->group(function () {
    Route::get('/registry', [App\Http\Controllers\RegistryController::class, 'index'])->name('registry.index');
    Route::get('/registry/{id}', [App\Http\Controllers\RegistryController::class, 'show'])->name('registry.show');
    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('logout');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login/quick', [App\Http\Controllers\Auth\LoginController::class, 'quick'])->name('login.quick');

Route::get('/auth/redirect/{provider}', [App\Http\Controllers\Auth\SocialController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/callback/{provider}', [App\Http\Controllers\Auth\SocialController::class, 'callback'])->name('social.callback');
