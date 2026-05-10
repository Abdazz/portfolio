<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localize'],
], function () {
    Route::view('/', 'home')->name('home');

    Route::view(LaravelLocalization::transRoute('routes.projects'), 'home')->name('projects.index');
    Route::view(LaravelLocalization::transRoute('routes.resume'), 'home')->name('resume');
    Route::view(LaravelLocalization::transRoute('routes.contact'), 'home')->name('contact');
});
