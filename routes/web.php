<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminUserRoleController;

Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listings/{id}', [ListingController::class, 'show'])->name('listings.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/listings', [ListingController::class, 'admin'])
        ->name('listings.admin');

    Route::post('/admin/listings', [ListingController::class, 'store'])
        ->name('admin.listings.store');

    Route::put('/admin/listings/{listing}', [ListingController::class, 'update'])
        ->name('admin.listings.update');

    Route::delete('/admin/listings/{listing}', [ListingController::class, 'destroy'])
        ->name('admin.listings.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{listing}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{listing}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/admin/users', [AdminUserRoleController::class, 'list']);
    Route::post('/admin/users/update', [AdminUserRoleController::class, 'update']);
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/profile', function () {
    return auth()->user();
})->middleware('auth');
