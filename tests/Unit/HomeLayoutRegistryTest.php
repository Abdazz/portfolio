<?php

use App\Services\Home\HomeLayoutRegistry;

it('resolves known layout slugs to view paths', function () {
    $registry = new HomeLayoutRegistry;
    expect($registry->view('gerold-01'))->toBe('home.layouts.gerold-01');
    expect($registry->view('gerold-10'))->toBe('home.layouts.gerold-10');
});

it('falls back to the default layout for unknown slugs', function () {
    $registry = new HomeLayoutRegistry;
    expect($registry->view('does-not-exist'))->toBe('home.layouts.gerold-01');
});

it('exposes the five layouts as select options', function () {
    $registry = new HomeLayoutRegistry;
    expect(array_keys($registry->selectOptions()))
        ->toBe(['gerold-01', 'gerold-02', 'gerold-03', 'gerold-08', 'gerold-10']);
});
