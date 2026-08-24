<?php

namespace App\Livewire;

use App\Models\ContactMessage;
use App\Services\TurnstileService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    #[Validate('required|string|min:2|max:100')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:150')]
    public string $subject = '';

    #[Validate('required|string|min:10|max:5000')]
    public string $message = '';

    /** Honeypot — must remain blank */
    public string $website = '';

    /** Cloudflare Turnstile token, set by the widget on success */
    public string $cfTurnstileResponse = '';

    public bool $submitted = false;

    public function send(TurnstileService $turnstile): void
    {
        // Honeypot check — silently swallow without error to confuse bots
        if (filled($this->website)) {
            $this->submitted = true;

            return;
        }

        // Rate limiting: 3 submissions per 10 minutes per IP
        $key = 'contact:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $this->addError('rate_limit', __('contact.rate_limited'));

            return;
        }

        // Turnstile verification
        if (! $turnstile->verify($this->cfTurnstileResponse, request()->ip())) {
            $this->addError('captcha', __('contact.captcha_failed'));

            return;
        }

        $this->validate();

        ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject ?: null,
            'message' => $this->message,
            'ip_address' => request()->ip(),
            'locale' => app()->getLocale(),
        ]);

        RateLimiter::hit($key, 600);

        $this->reset('name', 'email', 'subject', 'message', 'cfTurnstileResponse');
        $this->submitted = true;
    }

    public function render(): View
    {
        return view('livewire.contact-form');
    }
}
