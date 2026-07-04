<?php

use Illuminate\Support\Facades\Blade;

it('renders a section heading with eyebrow and title', function () {
    $html = Blade::render('<x-molecules.section-heading eyebrow="Work" title="Selected Projects" />');

    expect($html)->toContain('Work')->toContain('Selected Projects');
});

it('renders a stat counter with its target value and label', function () {
    $html = Blade::render('<x-molecules.stat-counter :value="12" label="Projects" />');

    expect($html)->toContain('12')->toContain('Projects')->toContain('x-data');
});

it('renders a marquee wrapping its slot twice for a seamless loop', function () {
    $html = Blade::render('<x-molecules.marquee><span>ITEM</span></x-molecules.marquee>');

    expect(substr_count($html, 'ITEM'))->toBeGreaterThanOrEqual(2);
});
