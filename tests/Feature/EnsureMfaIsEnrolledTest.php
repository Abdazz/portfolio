<?php

use App\Http\Middleware\EnsureMfaIsEnrolled;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Register a minimal test route protected by the middleware
    Route::middleware([EnsureMfaIsEnrolled::class])
        ->get('/test-mfa-guard', fn () => response('OK'));
});

test('user with MFA enrolled passes the middleware', function () {
    $user = User::factory()->create([
        'app_authentication_secret' => 'JBSWY3DPEHPK3PXP', // any non-null secret
    ]);

    $this->actingAs($user)
        ->get('/test-mfa-guard')
        ->assertOk();
});

test('user without MFA enrolled is blocked with 403', function () {
    $user = User::factory()->create([
        'app_authentication_secret' => null,
    ]);

    $this->actingAs($user)
        ->get('/test-mfa-guard')
        ->assertForbidden();
});

test('unauthenticated request passes through (middleware only guards authenticated users)', function () {
    $this->get('/test-mfa-guard')
        ->assertOk(); // no Auth user → no HasAppAuthentication check
});
