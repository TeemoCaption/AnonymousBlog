<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;  // 會員資料表
use Illuminate\Support\Facades\Hash;  // 導入加密用的類別

// 導入寄信需要的類別
use Illuminate\Support\Facades\Mail;
use App\Mail\SendVerifyMail;  // 自定義的寄信樣式、內容



class UserController extends Controller
{
    public function register(Request $request)
    {
        //dd($request->all());
        // 驗證請求數據
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users',
            'username' => 'required',
            'password' => 'required',
        ]);

        // 儲存用戶數據
        $user = User::create([
            'email' => $validatedData['email'],
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']),
            'verified' => false, // 默認未驗證
        ]);

        // 發送認證信
        Mail::to($user->email)->send(new SendVerifyMail($user));

        // 返回響應（例如，用戶資料或成功訊息）
        return response()->json($user);
    }

    public function verifyEmail($id, $hash)
    {
        // 尋找用戶
        $user = User::find($id);

        // 檢查使用者是否存在以及雜湊值是否匹配
        if ($user && sha1($user->email) == $hash) {
            // 更新 verified 欄位為 true
            $user->verified = true;
            $user->save();

            // 選用：在這裡新增額外的邏輯，例如重定向到登入頁面或顯示確認訊息
            return redirect()->route('verifysuccess');
        }

        // 如果驗證失敗，顯示錯誤訊息或重定向
        return redirect('/register')->with('error', '驗證失敗。');
    }
}
