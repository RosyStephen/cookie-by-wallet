<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});






Route::group(['prefix' => 'account'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/login', [LoginController::class, 'index'])->name('account.login');
        Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('account.authenticate');
        Route::get('/register', [LoginController::class, 'register'])->name('account.register');
        Route::post('/process-register', [LoginController::class, 'processRegister'])->name('account.processRegister');
    });
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('account.dashboard');
        Route::get('/profile', [LoginController::class, 'profile'])->name('account.profile');
        Route::get('/logout', [LoginController::class, 'logout'])->name('account.logout');
        Route::post('update-profile',[LoginController::class,'updateProfile'])->name('account.updateProfile');

    });
});
