<?php

use App\Http\Controllers\Admin\AspirationController;
use App\Http\Controllers\Admin\AuditLogController;
use Illuminate\Support\Facades\Route;

// ===== FRONTEND CONTROLLERS =====
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\EventController as FrontendEventController;
use App\Http\Controllers\Frontend\GalleryController as FrontendGalleryController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;
use App\Http\Controllers\Frontend\ProgramController as FrontendProgramController;

// ===== ADMIN CONTROLLERS =====
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SiteContentController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Frontend\OrganizationController;
use App\Http\Controllers\Frontend\SitemapController;

/*
|--------------------------------------------------------------------------
| FRONTEND
|--------------------------------------------------------------------------
*/

Route::middleware(['setlocale'])->group(function () {

    Route::get('/', fn() => redirect('/id'))->name('root');

    Route::prefix('{locale}')
        ->whereIn('locale', ['id', 'en'])
        ->group(function () {

            Route::get('/', [HomeController::class, 'index'])->name('home');

            // Berita
            Route::get('/berita', [FrontendPostController::class, 'index'])->name('frontend.posts.index');
            Route::get('/berita/{slug}', [FrontendPostController::class, 'show'])->name('frontend.posts.show');

            // Tentang BEM
            Route::get('/tentang', [FrontendPageController::class, 'about'])->name('frontend.about');

            // Agenda
            Route::get('/agenda', [FrontendEventController::class, 'index'])->name('frontend.events');
            Route::get('/agenda/{slug}', [FrontendEventController::class, 'show'])->name('frontend.events.show');

            // Galeri
            Route::get('/galeri', [FrontendGalleryController::class, 'index'])->name('frontend.gallery');

            // Kontak & Aspirasi
            Route::get('/kontak', [FrontendContactController::class, 'index'])->name('frontend.contact');
            Route::post('/kontak', [FrontendContactController::class, 'store'])->name('frontend.contact.store');

            Route::get('/cek-aspirasi', [FrontendContactController::class, 'checkStatus'])->name('frontend.contact.check');
            Route::post('/cek-aspirasi', [FrontendContactController::class, 'showStatus'])->name('frontend.contact.status');

            Route::get('/struktur-organisasi', [OrganizationController::class, 'index'])->name('frontend.organization');

            Route::get('/program-kerja', [FrontendProgramController::class, 'index'])->name('frontend.programs');
            Route::get('/program-kerja/{slug}', [FrontendProgramController::class, 'show'])->name('frontend.programs.show');

            // Halaman statis (catch-all, WAJIB PALING BAWAH)
            Route::get('/{slug}', [FrontendPageController::class, 'show'])->name('frontend.pages.show');
        });
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

/*
|--------------------------------------------------------------------------
| AUTH
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
| ADMIN
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

        // Route::view('/menus', 'admin.dashboard.index')->name('menus.index');
        Route::view('/translations', 'admin.dashboard.index')->name('translations.index');
        Route::view('/logs', 'admin.dashboard.index')->name('logs.index');

        Route::get('/site-contents', [SiteContentController::class, 'index'])->name('site-contents.index');
        Route::put('/site-contents', [SiteContentController::class, 'update'])->name('site-contents.update');
        // Route::view('/site-contents', 'admin.dashboard.index')->name('site-contents.index');

        Route::get('/translations', [TranslationController::class, 'index'])->name('translations.index');
        Route::post('/translations', [TranslationController::class, 'store'])->name('translations.store');
        Route::delete('/translations/{translation}', [TranslationController::class, 'destroy'])->name('translations.destroy');

        Route::get('/logs', [AuditLogController::class, 'index'])->name('logs.index');

        Route::prefix('aspirations')->name('aspirations.')->group(function () {
            Route::get('/', [AspirationController::class, 'index'])->name('index');
            Route::get('/{aspiration}', [AspirationController::class, 'show'])->name('show');
            Route::post('/{aspiration}/reply', [AspirationController::class, 'reply'])->name('reply');
            Route::put('/{aspiration}/status', [AspirationController::class, 'updateStatus'])->name('status');
            Route::delete('/{aspiration}', [AspirationController::class, 'destroy'])->name('destroy');
        });

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('tags', TagController::class)->except(['show', 'create', 'edit']);
        Route::resource('posts', AdminPostController::class)->except(['show']);
        Route::resource('pages', PageController::class)->except(['show']);
        Route::resource('events', EventController::class)->except(['show']);
        Route::resource('menus', MenuController::class)->except(['show', 'create', 'edit']);
        Route::resource('departments', DepartmentController::class)->except(['show', 'create', 'edit']);
        Route::resource('positions', PositionController::class)->except(['show', 'create', 'edit']);
        Route::resource('members', MemberController::class)->except(['show']);
        Route::resource('programs', AdminProgramController::class)->except(['show']);
    });
