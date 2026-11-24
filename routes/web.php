<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TagController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// === ADMIN CMS ===
Route::middleware(['auth', 'role:Super Admin|Admin BEM|Sekretaris|Humas|Departemen|Viewer'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::view('/dashboard', 'admin.dashboard.index')->name('dashboard');

        // Dummy routes sementara (akan diganti di tahap berikutnya)
        Route::view('/media', 'admin.dashboard.index')->name('media.index');
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('roles', RoleController::class)->except(['show', 'create', 'edit']);
        // Route::view('/users', 'admin.dashboard.index')->name('users.index');
        // Route::view('/roles', 'admin.dashboard.index')->name('roles.index');
        Route::view('/menus', 'admin.dashboard.index')->name('menus.index');
        Route::view('/translations', 'admin.dashboard.index')->name('translations.index');
        Route::view('/logs', 'admin.dashboard.index')->name('logs.index');
        Route::view('/site-contents', 'admin.dashboard.index')->name('site-contents.index');
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('tags', TagController::class)->except(['show', 'create', 'edit']);
        Route::resource('posts', PostController::class)->except(['show']);
        Route::resource('pages', PageController::class)->except(['show']);
        Route::resource('events', EventController::class)->except(['show']);
        // Route::view('/posts', 'admin.dashboard.index')->name('posts.index');
        // Route::view('/pages', 'admin.dashboard.index')->name('pages.index');
        // Route::view('/categories', 'admin.dashboard.index')->name('categories.index');
        // Route::view('/tags', 'admin.dashboard.index')->name('tags.index');
        // Route::view('/events', 'admin.dashboard.index')->name('events.index');
        Route::view('/settings', 'admin.dashboard.index')->name('settings.index');
    });

require __DIR__ . '/auth.php';
