<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;  // 會員資料表

// 驗證類別
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Hash;  // 導入加密用的類別

// 導入寄信需要的類別
use Illuminate\Support\Facades\Mail;
use App\Mail\SendVerifyMail;  // 自定義的寄信樣式、內容


class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            // 手動建立驗證器
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
                'username' => 'required|unique:users',
                'password' => 'required',
            ]);

            // 手動驗證請求數據
            if ($validator->fails()) {
                // 拋出驗證例外
                throw new ValidationException($validator);
            }

            $validatedData = $validator->validated();

            // 儲存使用者數據
            $user = User::create([
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
                'password' => Hash::make($validatedData['password']),
                'verified' => false, // 預設未驗證
            ]);

            // 傳送認證信
            Mail::to($user->email)->send(new SendVerifyMail($user));

            // 回傳回應
            return response()->json($user);
        } catch (ValidationException $e) {
            // 檢查具體是哪個欄位未通過驗證
            $errors = $e->validator->errors();

            $errorMessages = [];
            if ($errors->has('email')) {
                // 如果是郵箱欄位所造成的驗證錯誤，新增特定的錯誤訊息
                $errorMessages['email'] = '信箱已被使用';
            }

            if ($errors->has('username')) {
                // 如果是用戶名字段所造成的驗證錯誤，加入特定的錯誤訊息
                $errorMessages['username'] = '用戶名已被使用';
            }

            // 如果有具體的錯誤訊息，回傳這些訊息
            if (!empty($errorMessages)) {
                return response()->json(['error' => $errorMessages], 401);
            }

            // 傳回其他驗證錯誤
            return response()->json(['error' => $errors->all()], 422);
        }
    }

    // 點擊驗證信按鈕後的驗證動作
    public function verifyEmail($id, $hash)
    {
        // 尋找用戶
        $user = User::find($id);

        // 檢查使用者是否存在以及雜湊值是否匹配(即驗證成功)
        if ($user && sha1($user->email) == $hash) {
            // 更新 verified 欄位為 true
            $user->verified = true;
            $user->save();

            // 重定向到顯示驗證成功提示訊息頁面
            return redirect()->route('verifysuccess');
        }

        // 如果驗證失敗，顯示錯誤訊息或重定向
        return redirect('/register')->with('error', '驗證失敗。');
    }

    // 會員登入驗證
    public function loginVerify(Request $request)
    {
        // 驗證請求數據
        $validatedData = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $validatedData['username'])->first();

        if ($user && Hash::check($validatedData['password'], $user->password)) {
            // 返回響應
            return response()->json($user);
        } else {
            // 登入失敗，返回錯誤訊息
            return response()->json(['error' => '用戶名或密碼錯誤'], 401);
        }
    }
}
