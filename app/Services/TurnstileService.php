<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TurnstileService
{
    public function verify(string $token, ?string $ip = null): bool
    {
        $secret = config('services.turnstile.secret_key');

        if (empty($secret)) {
            // Secret not configured — skip verification in dev/test
            return true;
        }

        $response = Http::timeout(5)
            ->asForm()
            ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $ip,
            ]);

        return $response->ok() && $response->json('success') === true;
    }
}
