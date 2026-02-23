<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'index'])->name('home');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin/drafts', [App\Http\Controllers\Admin\DraftReviewController::class, 'index'])->name('admin.drafts.index');
    Route::get('/admin/drafts/create', [App\Http\Controllers\Admin\DraftReviewController::class, 'create'])->name('admin.drafts.create');
    Route::post('/admin/drafts', [App\Http\Controllers\Admin\DraftReviewController::class, 'store'])->name('admin.drafts.store');
    Route::get('/admin/drafts/section/{section}', [App\Http\Controllers\Admin\DraftReviewController::class, 'section'])->name('admin.drafts.section');
    Route::get('/admin/drafts/subsection/{draft}', [App\Http\Controllers\Admin\DraftReviewController::class, 'show'])->name('admin.drafts.show');
    Route::put('/admin/drafts/subsection/{draft}', [App\Http\Controllers\Admin\DraftReviewController::class, 'update'])->name('admin.drafts.update');

    Route::resource('/admin/users', App\Http\Controllers\Admin\UserController::class)->except(['show'])->names('admin.users');
});

require __DIR__.'/auth.php';
