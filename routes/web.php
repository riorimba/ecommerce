<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
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

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [AuthenticationController::class, 'register'])->name('register');
    Route::post('/store', [AuthenticationController::class, 'store'])->name('store');
    Route::get('/login', [AuthenticationController::class, 'login'])->name('login');
    Route::post('/authenticate', [AuthenticationController::class, 'authenticate'])->name('authenticate');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', [AuthenticationController::class, 'dashboard'])->name('dashboard');
    Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');

    Route::group(['middleware' => ['role:1']], function () {
        Route::get('categories/{category}/getProducts', [CategoryController::class, 'getProducts'])->name('categories.getProducts');
        Route::get('categories/getCategories', [CategoryController::class, 'getCategories'])->name('categories.getCategories');
        Route::resource('categories', CategoryController::class);
        Route::get('products/getProducts', [ProductController::class, 'getProducts'])->name('products.getProducts');
        Route::resource('products', ProductController::class);
        Route::get('users/getUsers', [UserController::class, 'getUsers'])->name('users.getUsers');
        Route::resource('users', UserController::class);
        Route::delete('products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.delete_image');
    });
    
    Route::resource('orders', OrderController::class);
});
