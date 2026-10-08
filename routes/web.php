<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;

Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(array_key_exists($locale, config('site.locales')), 404);
    session(['locale' => $locale]);
    \Illuminate\Support\Facades\Cookie::queue('locale', $locale, 60 * 24 * 365);

    return redirect()->to(url()->previous(url('/')));
})->name('lang');

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/story', [SiteController::class, 'story'])->name('site.story');
Route::get('/career', [SiteController::class, 'careerPage'])->name('site.career');
Route::get('/journal', [SiteController::class, 'journalPage'])->name('site.journal');
Route::get('/gallery', [SiteController::class, 'galleryPage'])->name('site.gallery');
Route::get('/career/{id}', [SiteController::class, 'careerDetail'])->whereNumber('id')->name('site.career.detail');
Route::get('/journal/{id}', [SiteController::class, 'journalDetail'])->whereNumber('id')->name('site.journal.detail');
Route::redirect('/honours', '/career', 301)->name('site.honours');
Route::redirect('/sources', '/', 301)->name('site.sources');
Route::get('/contact', [SiteController::class, 'contact'])->name('site.contact');
Route::post('/contact', [SiteController::class, 'sendContact'])->middleware('throttle:6,1')->name('contact.send');

require __DIR__.'/admin.php';
