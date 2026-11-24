<?php

use Illuminate\Support\Facades\Route;

// ===== FRONTEND CONTROLLERS =====
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController;

// ===== ADMIN CONTROLLERS =====
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;

/*
|--------------------------------------------------------------------------
| FRONTEND — WAJIB PALING ATAS
|--------------------------------------------------------------------------
*/

Route::middleware(['setlocale'])->group(function () {

    Route::get('/', fn() => redirect('/id'))->name('root');

    Route::prefix('{locale}')
        ->whereIn('locale', ['id', 'en'])
        ->group(function () {

            Route::get('/', [HomeController::class, 'index'])->name('home');

            Route::get('/berita', [FrontendPostController::class, 'index'])->name('frontend.posts.index');
            Route::get('/berita/{slug}', [FrontendPostController::class, 'show'])->name('frontend.posts.show');

            Route::view('/tentang', 'frontend.placeholder')->name('frontend.about');
            Route::view('/agenda', 'frontend.placeholder')->name('frontend.events');
            Route::view('/galeri', 'frontend.placeholder')->name('frontend.gallery');
            Route::view('/kontak', 'frontend.placeholder')->name('frontend.contact');
        });
});

/*
|--------------------------------------------------------------------------
| AUTH (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN CMS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Super Admin|Admin BEM|Sekretaris|Humas|Departemen|Viewer'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::view('/dashboard', 'admin.dashboard.index')->name('dashboard');

        Route::prefix('media')->name('media.')->group(function () {
            Route::get('/', [MediaController::class, 'index'])->name('index');
            Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
            Route::post('/folders', [MediaController::class, 'createFolder'])->name('folders.store');
            Route::delete('/folders/{folder}', [MediaController::class, 'deleteFolder'])->name('folders.destroy');
            Route::delete('/files/{media}', [MediaController::class, 'destroy'])->name('destroy');
            Route::post('/files/{media}/move', [MediaController::class, 'move'])->name('move');
        });

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::put('/identity', [SettingController::class, 'updateIdentity'])->name('update-identity');
            Route::put('/branding', [SettingController::class, 'updateBranding'])->name('update-branding');
            Route::put('/seo', [SettingController::class, 'updateSeo'])->name('update-seo');
            Route::put('/email', [SettingController::class, 'updateEmail'])->name('update-email');
            Route::put('/footer', [SettingController::class, 'updateFooter'])->name('update-footer');
        });

        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('roles', RoleController::class)->except(['show', 'create', 'edit']);

        Route::view('/menus', 'admin.dashboard.index')->name('menus.index');
        Route::view('/translations', 'admin.dashboard.index')->name('translations.index');
        Route::view('/logs', 'admin.dashboard.index')->name('logs.index');
        Route::view('/site-contents', 'admin.dashboard.index')->name('site-contents.index');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('tags', TagController::class)->except(['show', 'create', 'edit']);
        Route::resource('posts', AdminPostController::class)->except(['show']);
        Route::resource('pages', PageController::class)->except(['show']);
        Route::resource('events', EventController::class)->except(['show']);
    });
