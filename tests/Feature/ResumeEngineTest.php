<?php

use App\Contracts\PdfRenderer;
use App\Models\Experience;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

// ─── Helpers ─────────────────────────────────────────────────────────────────

/**
 * Middleware that trigger locale-redirect loops in tests.
 *
 * @return list<class-string>
 */
function resumeLocaleMiddleware(): array
{
    return [
        LaravelLocalizationRedirectFilter::class,
        LocaleSessionRedirect::class,
    ];
}

/**
 * A fake PdfRenderer that writes a minimal PDF stub to disk.
 * Allows download tests to run without Chromium.
 */
class FakePdfRenderer implements PdfRenderer
{
    public function render(string $url, string $absolutePath): void
    {
        file_put_contents($absolutePath, '%PDF-1.4 fake');
    }
}

// Register the fake renderer before every test in this file.
beforeEach(function () {
    $this->app->bind(PdfRenderer::class, FakePdfRenderer::class);
});

// ─── Print route ──────────────────────────────────────────────────────────────

test('resume print route returns 200', function () {
    $this->withoutMiddleware(resumeLocaleMiddleware())
        ->get('/resume/print')
        ->assertOk();
});

test('resume print route renders profile name when a profile exists', function () {
    Profile::factory()->create(['full_name' => 'Jane Doe']);

    $this->withoutMiddleware(resumeLocaleMiddleware())
        ->get('/resume/print')
        ->assertOk()
        ->assertSee('Jane Doe');
});

test('resume print route includes experience entries', function () {
    Experience::factory()->create([
        'title' => ['en' => 'Senior Developer', 'fr' => 'Développeur Senior'],
        'company' => 'Acme Corp',
    ]);

    $this->withoutMiddleware(resumeLocaleMiddleware())
        ->get('/resume/print')
        ->assertOk()
        ->assertSee('Senior Developer');
});

// ─── Download route ───────────────────────────────────────────────────────────

test('resume download route returns a PDF response', function () {
    $response = $this->withoutMiddleware(resumeLocaleMiddleware())
        ->get('/resume/download');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/pdf');
});

test('resume download caches the PDF on the first request', function () {
    // Ensure no leftover cache.
    Storage::disk('local')->deleteDirectory('resume/pdf');

    $this->withoutMiddleware(resumeLocaleMiddleware())
        ->get('/resume/download')
        ->assertOk();

    $files = Storage::disk('local')->allFiles('resume/pdf');
    expect($files)->not->toBeEmpty();

    // Clean up.
    Storage::disk('local')->deleteDirectory('resume/pdf');
});

// ─── JSON export ──────────────────────────────────────────────────────────────

test('resume JSON export returns 200 with correct top-level keys', function () {
    $this->withoutMiddleware(resumeLocaleMiddleware())
        ->getJson('/resume/export.json')
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'locale',
                'template',
                'profile',
                'experiences',
                'education',
                'skills',
                'certifications',
                'languages',
            ],
        ]);
});

test('resume JSON export includes experience data', function () {
    Experience::factory()->create([
        'title' => ['en' => 'Lead Engineer', 'fr' => 'Ingénieur Principal'],
        'company' => 'Globex',
    ]);

    $this->withoutMiddleware(resumeLocaleMiddleware())
        ->getJson('/resume/export.json')
        ->assertOk()
        ->assertJsonPath('data.experiences.0.title', 'Lead Engineer')
        ->assertJsonPath('data.experiences.0.company', 'Globex');
});

test('resume JSON export locale key reflects app locale', function () {
    $this->withoutMiddleware(resumeLocaleMiddleware())
        ->getJson('/resume/export.json')
        ->assertOk()
        ->assertJsonPath('data.locale', app()->getLocale());
});
