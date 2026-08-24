<?php

use App\Livewire\ContactForm;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

// ─── Helpers ─────────────────────────────────────────────────────────────────

/** Valid payload for the contact form. */
function validContactData(): array
{
    return [
        'name' => 'Alice Dupont',
        'email' => 'alice@example.com',
        'subject' => 'Hello',
        'message' => 'I would love to get in touch with you about a project.',
    ];
}

// ─── Validation ───────────────────────────────────────────────────────────────

test('contact form validates required fields', function () {
    Livewire::test(ContactForm::class)
        ->call('send')
        ->assertHasErrors(['name', 'email', 'message']);
});

test('contact form validates email format', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'Alice')
        ->set('email', 'not-an-email')
        ->set('message', 'This is a valid message body.')
        ->call('send')
        ->assertHasErrors(['email']);
});

test('contact form validates minimum message length', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'Alice')
        ->set('email', 'alice@example.com')
        ->set('message', 'Too short')
        ->call('send')
        ->assertHasErrors(['message']);
});

// ─── Golden path ──────────────────────────────────────────────────────────────

test('valid submission creates a contact message and shows success state', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'Alice Dupont')
        ->set('email', 'alice@example.com')
        ->set('subject', 'Hello')
        ->set('message', 'I would love to get in touch with you about a project.')
        ->call('send')
        ->assertHasNoErrors()
        ->assertSet('submitted', true);

    expect(ContactMessage::count())->toBe(1);

    $this->assertDatabaseHas('contact_messages', [
        'name' => 'Alice Dupont',
        'email' => 'alice@example.com',
    ]);
});

test('subject is optional', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'Alice Dupont')
        ->set('email', 'alice@example.com')
        ->set('message', 'I would love to get in touch with you about a project.')
        ->call('send')
        ->assertHasNoErrors()
        ->assertSet('submitted', true);

    $this->assertDatabaseHas('contact_messages', ['subject' => null]);
});

// ─── Honeypot ─────────────────────────────────────────────────────────────────

test('honeypot field silently blocks bot submissions', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'Bot')
        ->set('email', 'bot@spam.io')
        ->set('message', 'Buy cheap watches now!!!')
        ->set('website', 'http://spam.example.com') // honeypot filled
        ->call('send')
        ->assertSet('submitted', true); // looks like success to the bot

    expect(ContactMessage::count())->toBe(0); // nothing persisted
});

// ─── Rate limiting ────────────────────────────────────────────────────────────

test('contact form is rate limited after 3 submissions', function () {
    $rateLimiterKey = 'contact:127.0.0.1';
    RateLimiter::clear($rateLimiterKey);

    // Exhaust the 3 allowed attempts
    foreach (range(1, 3) as $i) {
        Livewire::test(ContactForm::class)
            ->set('name', 'Alice')
            ->set('email', 'alice@example.com')
            ->set('message', 'Test message number '.$i.' for rate limiting.')
            ->call('send')
            ->assertSet('submitted', true);
    }

    // 4th attempt must be blocked
    Livewire::test(ContactForm::class)
        ->set('name', 'Alice')
        ->set('email', 'alice@example.com')
        ->set('message', 'This fourth message should be rate limited.')
        ->call('send')
        ->assertHasErrors(['rate_limit'])
        ->assertSet('submitted', false);

    RateLimiter::clear($rateLimiterKey);
});
