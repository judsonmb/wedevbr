<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MerchantController;

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
        Route::get('/{user}', [UserController::class, 'read'])->name('user.read');
        Route::put('/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{user}', [UserController::class, 'delete'])->name('user.delete');
    });

    Route::prefix('merchants')->group(function () {
        Route::post('/', [MerchantController::class, 'create'])->name('merchant.create');
        Route::get('/{id}', [MerchantController::class, 'read'])->name('merchant.read');
        Route::put('/{merchant}', [MerchantController::class, 'update'])->name('merchant.update');
        Route::delete('/{merchant}', [MerchantController::class, 'delete'])->name('merchant.delete');
    });
});
