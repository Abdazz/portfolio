<?php

use Illuminate\Support\Facades\Blade;

it('reserved sections render nothing when empty', function () {
    foreach (['services', 'testimonials', 'blog'] as $section) {
        $html = Blade::render("<x-organisms.sections.$section />");
        expect(trim($html))->toBe('');
    }
});
