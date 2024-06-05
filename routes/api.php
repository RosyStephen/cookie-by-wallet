<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\CookieController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');




// Registration route
Route::post('/register', [AuthController::class, 'register'])->name('api.user.register');

// Login route
Route::post('/login', [AuthController::class, 'login'])->name('api.user.login');



Route::group([
    'middleware' => 'auth:sanctum',
], function(){
    Route::post('/subscribe', [CookieController::class, 'subscribe'])->name('api.cookie.subscribe');
    Route::post('/add-money', [WalletController::class, 'addMoney'])->name('api.wallet.addMoney');
    Route::post('/buy-cookie', [CookieController::class, 'buyCookie'])->name('api.wallet.buyCookie');
    Route::post('/logout', [AuthController::class, 'logout'])->name('pi.user.logout');
});
