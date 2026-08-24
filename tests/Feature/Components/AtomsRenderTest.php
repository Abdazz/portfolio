<?php

use Illuminate\Support\Facades\Blade;

it('renders a primary button with the gradient class', function () {
    $html = Blade::render('<x-atoms.button variant="primary">Go</x-atoms.button>');

    expect($html)->toContain('Go')
        ->toContain('gradient-primary')
        ->toContain('rounded-full');
});

it('renders an outline button as a bordered pill', function () {
    $html = Blade::render('<x-atoms.button variant="outline">More</x-atoms.button>');

    expect($html)->toContain('More')
        ->toContain('border-accent')
        ->toContain('rounded-full');
});

it('renders an anchor when href is provided', function () {
    $html = Blade::render('<x-atoms.button href="/contact">Contact</x-atoms.button>');

    expect($html)->toContain('<a')->toContain('href="/contact"');
});

it('renders a badge and a link', function () {
    expect(Blade::render('<x-atoms.badge>New</x-atoms.badge>'))->toContain('New');
    expect(Blade::render('<x-atoms.link href="/x">Read</x-atoms.link>'))
        ->toContain('Read')->toContain('href="/x"');
});
