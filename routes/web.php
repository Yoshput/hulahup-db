<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CanteenController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HomeController;

// Healthcheck endpoint for Railway
Route::get('/up', function () {
    return response()->json(['status' => 'ok']);
});

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
Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

// 4B. Admin Dashboard (Khusus Admin)
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->middleware('auth', 'admin')
    ->name('admin.dashboard');

// 4C. Admin Orders Monitoring
Route::get('/admin/orders', [AdminController::class, 'ordersIndex'])
    ->middleware('auth', 'admin')
    ->name('admin.orders.index');

// 4D. Admin Canteens Management
Route::get('/admin/canteens', [AdminController::class, 'canteensIndex'])
    ->middleware('auth', 'admin')
    ->name('admin.canteens.index');

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

// 6C. Canteen Routes (Ibu Kantin)
Route::middleware(['auth', 'checkRole:ibu_kantin'])->prefix('canteen')->group(function () {
    // Dashboard
    Route::get('/dashboard', [CanteenController::class, 'dashboard'])->name('canteen.dashboard');
    Route::get('/create', [CanteenController::class, 'create'])->name('canteen.create');
    Route::post('/', [CanteenController::class, 'store'])->name('canteen.store');

    // Menu Management
    Route::get('/menus', [CanteenController::class, 'menuIndex'])->name('canteen.menus.index');
    Route::get('/menus/create', [CanteenController::class, 'menuCreate'])->name('canteen.menus.create');
    Route::post('/menus', [CanteenController::class, 'menuStore'])->name('canteen.menus.store');
    Route::get('/menus/{id}/edit', [CanteenController::class, 'menuEdit'])->name('canteen.menus.edit');
    Route::put('/menus/{id}', [CanteenController::class, 'menuUpdate'])->name('canteen.menus.update');
    Route::delete('/menus/{id}', [CanteenController::class, 'menuDestroy'])->name('canteen.menus.destroy');

    // Voucher Management
    Route::get('/vouchers', [CanteenController::class, 'voucherIndex'])->name('canteen.vouchers.index');
    Route::get('/vouchers/create', [CanteenController::class, 'voucherCreate'])->name('canteen.vouchers.create');
    Route::post('/vouchers', [CanteenController::class, 'voucherStore'])->name('canteen.vouchers.store');
    Route::get('/vouchers/{id}/edit', [CanteenController::class, 'voucherEdit'])->name('canteen.vouchers.edit');
    Route::put('/vouchers/{id}', [CanteenController::class, 'voucherUpdate'])->name('canteen.vouchers.update');
    Route::delete('/vouchers/{id}', [CanteenController::class, 'voucherDestroy'])->name('canteen.vouchers.destroy');

    // Sales & Payments
    Route::get('/sales', [CanteenController::class, 'sales'])->name('canteen.sales');
    Route::get('/payments', [CanteenController::class, 'payments'])->name('canteen.payments');
    
    // Order Status Management
    Route::get('/orders', [\App\Http\Controllers\OrderStatusController::class, 'index'])->name('canteen.orders.index');
    Route::post('/orders/{order}/status', [\App\Http\Controllers\OrderStatusController::class, 'updateStatus'])->name('canteen.orders.updateStatus');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderStatusController::class, 'show'])->name('canteen.orders.show');
});

// 7. Halaman Lainnya
Route::get('/history', [OrderController::class, 'orderHistory'])->middleware('auth')->name('orders.history');
Route::get('/topup', function () { return view('topup'); })->middleware('auth');

// 7E. Active Orders Dashboard (Customer)
Route::get('/orders/active', [OrderController::class, 'activeOrders'])->middleware('auth')->name('orders.active');

// 7F. Order Details (for receipt/tracking)
Route::get('/api/orders/{order}', [OrderController::class, 'getOrderDetails'])->middleware('auth')->name('orders.details');

// 7G. Canteen Shop Listing & Details (Public)
Route::get('/canteens', [CanteenController::class, 'shop'])->middleware('auth')->name('canteens.shop');
Route::get('/canteens/{canteen}', [CanteenController::class, 'shopDetail'])->middleware('auth')->name('canteens.shop.detail');

// 7B. Cart Routes (for customers/users)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{menu}/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{menu}/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/summary', [CartController::class, 'summary'])->name('cart.summary');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

// 7C. Payment Routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/{payment}/status', [PaymentController::class, 'status'])->name('payment.status');
});

// 7D. Payment Webhook (No auth required for webhook)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// 8. Admin Routes - System Monitoring & User Management (NO canteen management)
Route::middleware(['auth', 'checkRole:admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Users Management (Monitor online users, etc)
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{user}/change-role', [UserController::class, 'changeRole'])->name('admin.users.changeRole');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // System Logs & Reports (add later)
});

