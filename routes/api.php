<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

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

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('users')->group(function () {
        Route::post('/', [UserController::class, 'create'])->name('user.create');
        Route::get('/{id}', [UserController::class, 'read'])->name('user.read');
        Route::put('/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{user}', [UserController::class, 'delete'])->name('user.delete');
    });

    Route::prefix('merchants')->group(function () {
        Route::post('/', [MerchantController::class, 'create'])->name('merchant.create');
        Route::get('/{id}', [MerchantController::class, 'read'])->name('merchant.read');
        Route::put('/{merchant}', [MerchantController::class, 'update'])->name('merchant.update');
        Route::delete('/{merchant}', [MerchantController::class, 'delete'])->name('merchant.delete');
    });

    Route::prefix('products')->group(function () {
        Route::post('/', [ProductController::class, 'create'])->name('product.create')
                                                              ->middleware('user.is.admin');
        Route::get('/{id}', [ProductController::class, 'read'])->name('product.read');
        Route::put('/{product}', [ProductController::class, 'update'])->name('product.update')
                                                                      ->middleware('user.is.admin');
        Route::delete('/{product}', [ProductController::class, 'delete'])->name('product.delete');
    });

    Route::prefix('orders')->group(function () {
        Route::post('/', [OrderController::class, 'create'])->name('order.create');
        Route::get('/{id}', [OrderController::class, 'read'])->name('order.read');
        Route::put('/{order}', [OrderController::class, 'update'])->name('order.update');
        Route::delete('/{order}', [OrderController::class, 'delete'])->name('order.delete');
    });
});
