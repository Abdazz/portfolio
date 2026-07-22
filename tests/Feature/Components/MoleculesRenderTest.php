<?php

use Illuminate\Support\Facades\Blade;

it('renders a section heading with a gradient title and lead', function () {
    $html = Blade::render('<x-molecules.section-heading title="Selected Projects">A short lead.</x-molecules.section-heading>');

    expect($html)->toContain('Selected Projects')->toContain('gradient-text')->toContain('A short lead.');
});

it('renders a stat counter with its target value and label', function () {
    $html = Blade::render('<x-molecules.stat-counter :value="12" label="Projects" />');

    expect($html)->toContain('12')->toContain('Projects')->toContain('x-data');
});

it('renders a marquee wrapping its slot twice for a seamless loop', function () {
    $html = Blade::render('<x-molecules.marquee><span>ITEM</span></x-molecules.marquee>');

    expect(substr_count($html, 'ITEM'))->toBeGreaterThanOrEqual(2);
});
