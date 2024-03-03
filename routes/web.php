<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

//Auth::routes(['verify' => true]);

// 定義一個路由群組，應用程式驗證中間件
Route::middleware(['auth'])->group(function () {  // 只有已驗證的會員才能連到的路由
    Route::get('/', function () {
        return view('index');
    });
});

Route::middleware(['guest'])->group(function () {  // 只有已登入的會員才能連到的路由
    // 登入頁
    Route::get('/login', function () {
        return view('login');
    })->name('login');
});

// 會員登入驗證
Route::post('/loginverify', [UserController::class, 'loginVerify']);

// 註冊會員
Route::get('/register', function () {
    return view('register');
});

// 註冊會員處理函數
Route::post('/register/save', [UserController::class, 'register']);

// 驗證通過後資料表驗證欄位更新，已阻止用戶訪問此路由
Route::get('/email/verify/{id}/{hash}/{token}', [UserController::class, 'verifyEmail'])->name('verification.verify');

// 驗證通過頁
Route::get('/verifysuccess', function () {
    return view('verifysuccess');
})->name('verifysuccess');

// 禁止訪問頁
Route::get('/noaccess', function () {
    return view('noaccess');
});

// 已經驗證過提示頁
Route::get('/verified', function () {
    return view('verified');
});

// 驗證連結失效
Route::get('/failverifiedurl', function () {
    return view('failverifiedurl');
});
