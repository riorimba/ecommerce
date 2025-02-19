<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\AuthenticationController;

use App\Http\Controllers\OrderController as OrderControllerWeb;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);
Route::post('logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
Route::post('forgot-password', [AuthenticationController::class, 'requestVerificationCode']);
Route::post('update-password', [AuthenticationController::class, 'updatePassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [UserController::class, 'me']);
    Route::post('update-profile', [UserController::class, 'updateProfile']);
    
    Route::get('products', [ProductController::class, 'index']);

    Route::post('cart/add', [CartController::class, 'addProduct']);
    Route::delete('cart/delete/{productId}', [CartController::class, 'deleteProduct']);
    Route::get('cart/count', [CartController::class, 'countProducts']);
    Route::post('cart/checkout', [CartController::class, 'checkout']);

    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{orderId}', [OrderController::class, 'show']);
    Route::get('orders/{id}/invoice', [OrderControllerWeb::class, 'downloadInvoice']);

    // Rute untuk notifikasi
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy']);
});


