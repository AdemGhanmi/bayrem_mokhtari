<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('cache.headers:private;no_store;no_cache;must_revalidate;max_age=0')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1')->name('authenticate');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'save'])->name('settings.save');

        Route::get('/pages', [PageController::class, 'index'])->name('pages');
        Route::get('/pages/{page}', [PageController::class, 'show'])->name('pages.show');
        Route::get('/pages/{page}/create', [PageController::class, 'create'])->name('pages.create');
        Route::post('/pages/{page}', [PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{page}/{section}/edit', [PageController::class, 'edit'])->whereNumber('section')->name('pages.edit');
        Route::put('/pages/{page}/{section}', [PageController::class, 'update'])->whereNumber('section')->name('pages.update');
        Route::delete('/pages/{page}/{section}', [PageController::class, 'destroy'])->whereNumber('section')->name('pages.destroy');

        Route::get('/messages', [MessageController::class, 'index'])->name('messages');
        Route::post('/messages/{message}/read', [MessageController::class, 'read'])->name('messages.read');
        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

        Route::prefix('content/{type}')->whereIn('type', ['career', 'honours', 'gallery', 'journal', 'video', 'diploma'])->name('content.')->group(function () {
            Route::get('/', [ContentController::class, 'index'])->name('index');
            Route::get('/create', [ContentController::class, 'create'])->name('create');
            Route::post('/', [ContentController::class, 'store'])->name('store');
            Route::post('/reorder', [ContentController::class, 'reorder'])->name('reorder');
            Route::get('/{id}/edit', [ContentController::class, 'edit'])->whereNumber('id')->name('edit');
            Route::put('/{id}', [ContentController::class, 'update'])->whereNumber('id')->name('update');
            Route::post('/{id}/toggle', [ContentController::class, 'toggle'])->whereNumber('id')->name('toggle');
            Route::delete('/{id}', [ContentController::class, 'destroy'])->whereNumber('id')->name('destroy');
        });
    });
});
