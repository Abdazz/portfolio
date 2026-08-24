<?php

use App\Models\SiteSetting;

it('defaults home_layout to gerold-01', function () {
    expect(SiteSetting::instance()->home_layout)->toBe('gerold-01');
});

it('persists a chosen home_layout', function () {
    $setting = SiteSetting::instance();
    $setting->update(['home_layout' => 'gerold-08']);
    expect(SiteSetting::instance()->fresh()->home_layout)->toBe('gerold-08');
});
