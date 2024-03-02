<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

Auth::routes(['verify' => true]);

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

// 會員登入
Route::post('/loginverify', [UserController::class, 'loginVerify']);

// 註冊會員
Route::post('/register/save', [UserController::class, 'register']);

// 驗證通過後資料表驗證欄位更新
Route::get('/email/verify/{id}/{hash}', [UserController::class, 'verifyEmail'])->name('verification.verify');

Route::get('/verifysuccess', function () {
    return view('verifysuccess');
})->name('verifysuccess');
