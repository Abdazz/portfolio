<?php

use App\Services\Resume\BrowsershotPdfRenderer;

it('builds chromium arguments with a single leading double-dash', function () {
    $command = (new BrowsershotPdfRenderer)
        ->configure('https://example.com/resume/print')
        ->createPdfCommand('/tmp/resume.pdf');

    $args = $command['options']['args'];

    expect($args)
        ->toContain('--disable-gpu')
        ->toContain('--disable-dev-shm-usage')
        ->toContain('--no-sandbox');

    foreach ($args as $arg) {
        expect($arg)->not->toStartWith('---');
    }
});
