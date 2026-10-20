<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ServiceController;
use App\Http\Middleware\PublicPageCache;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

// Public pages: no session or cookies (faster, CDN-cacheable). Only the contact form needs a session.
Route::withoutMiddleware([StartSession::class, ShareErrorsFromSession::class, PreventRequestForgery::class, AddQueuedCookiesToResponse::class, EncryptCookies::class])
    ->middleware(PublicPageCache::class)
    ->group(function () {
        Route::get('/', [PageController::class, 'home'])->name('home');
        Route::get('/about', [PageController::class, 'about'])->name('about');

        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

        Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
        Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])->name('portfolio.show');

        Route::get('/industries', [IndustryController::class, 'index'])->name('industries.index');
        Route::get('/industries/{slug}', [IndustryController::class, 'show'])->name('industries.show');

        Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
        Route::get('/blog/category/{category}', [BlogController::class, 'category'])->name('blog.category');
        Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

        Route::get('/privacy-policy', [PageController::class, 'legal'])->defaults('page', 'privacy')->name('legal.privacy');
        Route::get('/terms-and-conditions', [PageController::class, 'legal'])->defaults('page', 'terms')->name('legal.terms');
        Route::get('/cookie-policy', [PageController::class, 'legal'])->defaults('page', 'cookies')->name('legal.cookies');

        Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
        Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
        Route::get('/llms.txt', [SeoController::class, 'llms'])->name('llms');
    });

// Contact form (session + CSRF)
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::get('/contact/captcha', [ContactController::class, 'captcha'])->middleware('throttle:30,1')->name('contact.captcha');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:contact')->name('contact.store');

// 301 redirects for changed or common alternate URLs.
Route::permanentRedirect('/contact-us', '/contact');
Route::permanentRedirect('/about-us', '/about');
Route::permanentRedirect('/insights', '/blog');
