<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Midtrans Callback
Route::post('midtrans-callback', [OrderController::class, 'callback']);

// Email Verification
Route::get('/email/verify', [VerificationController::class, 'show'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
Route::post('/email/verification-notification', [VerificationController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Guest Routes
Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [AuthenticationController::class, 'register'])->name('register');
    Route::post('/store', [AuthenticationController::class, 'store'])->name('store');
    Route::get('/login', [AuthenticationController::class, 'login'])->name('login');
    Route::post('/authenticate', [AuthenticationController::class, 'authenticate'])->name('authenticate');
    
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated Routes
Route::post('logout', [AuthenticationController::class, 'logout'])->middleware('auth')->name('logout');

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('dashboard', [AuthenticationController::class, 'dashboard'])->name('dashboard');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::post('profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    
    // Admin Routes
    Route::group(['middleware' => ['role:1']], function () {
        // Notifications
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

        // Categories
        Route::get('categories/{category}/getProducts', [CategoryController::class, 'getProducts'])->name('categories.getProducts');
        Route::get('categories/getCategories', [CategoryController::class, 'getCategories'])->name('categories.getCategories');
        Route::get('categories/export', [CategoryController::class, 'export'])->name('categories.export');
        Route::post('categories/import', [CategoryController::class, 'import'])->name('categories.import');
        Route::get('categories/template', [CategoryController::class, 'template'])->name('categories.template');
        Route::resource('categories', CategoryController::class);
        
        // Products
        Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
        Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
        Route::get('products/template', [ProductController::class, 'template'])->name('products.template');
        Route::get('products/getProducts', [ProductController::class, 'getProducts'])->name('products.getProducts');
        Route::resource('products', ProductController::class);
        Route::delete('products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.delete_image');

        // Users
        Route::get('users/getUsers', [UserController::class, 'getUsers'])->name('users.getUsers');
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');
        Route::resource('users', UserController::class);

        // Orders
        Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
        Route::get('orders/{id}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.downloadInvoice');
        Route::resource('orders', OrderController::class);
    });
});
