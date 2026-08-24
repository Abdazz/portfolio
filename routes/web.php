<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\ResumeDownloadController;
use App\Http\Controllers\ResumeJsonController;
use App\Http\Controllers\ResumePrintController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localize'],
], function () {
    Route::get('/', HomeController::class)->name('home');

    Route::get(LaravelLocalization::transRoute('routes.projects'), [ProjectsController::class, 'index'])->name('projects.index');
    Route::get(LaravelLocalization::transRoute('routes.project'), [ProjectsController::class, 'show'])->name('projects.show');

    Route::get(LaravelLocalization::transRoute('routes.resume'), ResumeController::class)->name('resume');
    Route::get(LaravelLocalization::transRoute('routes.resume.print'), ResumePrintController::class)->middleware('throttle:30,1')->name('resume.print');
    Route::get(LaravelLocalization::transRoute('routes.resume.download'), ResumeDownloadController::class)->middleware('throttle:10,1')->name('resume.download');
    Route::get(LaravelLocalization::transRoute('routes.resume.json'), ResumeJsonController::class)->name('resume.json');

    Route::get(LaravelLocalization::transRoute('routes.contact'), ContactController::class)->name('contact');
});
