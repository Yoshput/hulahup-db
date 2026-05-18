<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\OrderController;

// 1. Halaman LANDING PAGE (Welcome) - Ini pintu masuk utama
Route::get('/', function () {
    return view('welcome'); // Ini akan memanggil file welcome.blade.php yang ada logo Hulahup
});

// 2. Halaman Login
Route::get('/login', function () {
    return view('auth.login'); // Pastikan file ada di resources/views/auth/login.blade.php
})->name('login');

// 3. Halaman Sign Up
Route::get('/signup', function () {
    return view('auth.signup');
})->name('signup');

// 4. Halaman Utama Dashboard (Setelah Login)
Route::get('/home', function () {
    return view('home'); 
})->middleware('auth');

// 4B. Admin Dashboard (Khusus Admin)
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->middleware('auth', 'admin')
    ->name('admin.dashboard');

// 5. Proses Logic Auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'store']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/topup', [AuthController::class, 'topup'])->middleware('auth')->name('topup');

// 6. Profile Routes
Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->middleware('auth')->name('profile.upload-photo');
Route::post('/profile/upload-avatar', [ProfileController::class, 'uploadAvatar'])->middleware('auth')->name('profile.upload.avatar');

// 6B. Order Routes (API endpoints)
Route::post('/api/orders', [OrderController::class, 'store'])->middleware('auth')->name('orders.store');

// 7. Halaman Lainnya
Route::get('/history', function () { return view('history'); })->middleware('auth');
Route::get('/topup', function () { return view('topup'); })->middleware('auth');

// 8. Admin Routes - Menu Management
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Menu Management
    Route::get('/menus', [MenuController::class, 'index'])->name('admin.menus.index');
    Route::get('/menus/create', [MenuController::class, 'create'])->name('admin.menus.create');
    Route::post('/menus', [MenuController::class, 'store'])->name('admin.menus.store');
    Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->name('admin.menus.edit');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('admin.menus.update');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('admin.menus.destroy');

    // Users Management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{user}/change-role', [UserController::class, 'changeRole'])->name('admin.users.changeRole');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Vouchers Management
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('admin.vouchers.index');
    Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('admin.vouchers.create');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('admin.vouchers.store');
    Route::get('/vouchers/{voucher}/edit', [VoucherController::class, 'edit'])->name('admin.vouchers.edit');
    Route::put('/vouchers/{voucher}', [VoucherController::class, 'update'])->name('admin.vouchers.update');
    Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy'])->name('admin.vouchers.destroy');
});

