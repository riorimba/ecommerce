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

Route::redirect('/', '/dashboard');
Route::post('midtrans-callback', [OrderController::class, 'callback']);


Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [AuthenticationController::class, 'register'])->name('register');
    Route::post('/store', [AuthenticationController::class, 'store'])->name('store');
    Route::get('/login', [AuthenticationController::class, 'login'])->name('login');
    Route::post('/authenticate', [AuthenticationController::class, 'authenticate'])->name('authenticate');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', [AuthenticationController::class, 'dashboard'])->name('dashboard');
    Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::post('profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
    
    Route::group(['middleware' => ['role:1']], function () {
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

        Route::get('categories/{category}/getProducts', [CategoryController::class, 'getProducts'])->name('categories.getProducts');
        Route::get('categories/getCategories', [CategoryController::class, 'getCategories'])->name('categories.getCategories');
        Route::get('categories/export', [CategoryController::class, 'export'])->name('categories.export');
        Route::post('categories/import', [CategoryController::class, 'import'])->name('categories.import');
        Route::get('categories/template', [CategoryController::class, 'template'])->name('categories.template');
        Route::resource('categories', CategoryController::class);
        
        Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
        Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
        Route::get('products/template', [ProductController::class, 'template'])->name('products.template');
        Route::get('products/getProducts', [ProductController::class, 'getProducts'])->name('products.getProducts');
        Route::resource('products', ProductController::class);
        Route::delete('products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.delete_image');

        Route::get('users/getUsers', [UserController::class, 'getUsers'])->name('users.getUsers');
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');
        Route::resource('users', UserController::class);

        Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
        Route::get('orders/{id}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.downloadInvoice');
        Route::resource('orders', OrderController::class);
    });
    
});
