<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;  // 會員資料表

// 驗證類別
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;  // 導入加密用的類別
use Illuminate\Support\Str;


// 導入寄信需要的類別
use Illuminate\Support\Facades\Mail;
use App\Mail\SendVerifyMail;  // 自定義的寄信樣式、內容


class UserController extends Controller
{
    // 註冊會員處理函數
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

            // 產生一個新的、唯一的驗證 token
            $verificationToken = Str::random(60);

            // 儲存使用者數據
            $user = User::create([
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
                'password' => Hash::make($validatedData['password']),
                'verified' => false, // 預設未驗證
                'verification_token' => $verificationToken, // 儲存驗證 token
            ]);

            // 傳送認證信
            Mail::to($user->email)->send(new SendVerifyMail($user, $verificationToken));

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

    // 點擊驗證按鈕後的處理
    public function verifyEmail(Request $request, $id, $hash, $token)
    {
        //dd($token);
        $user = User::find($id);

        // 檢查使用者是否存在、雜湊值是否匹配，以及驗證 token 是否匹配
        if ($user && sha1($user->email) == $hash && $user->verification_token == $token) {
            if ($user->verified == true) {
                return redirect('/verified');
            } else {
                // 更新 verified 欄位為 true
                $user->verified = true;
                //$user->verification_token = null;
                $user->save();
            }


            return redirect()->route('verifysuccess');
        }

        return redirect('/verified');
    }

    // 會員登入驗證
    public function loginVerify(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // 嘗試將用戶驗證為應用程式
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->verified != 1) {
                // 如果使用者存在，密碼正確，但未驗證
                return response()->json(['error' => '請先至信箱點擊認證信驗證按鈕進行驗證(有時可能在垃圾郵件中)'], 401);
            }

            // 如果使用者存在，密碼正確，並且已驗證，返回使用者資訊
            return response()->json($user);
        }

        // 如果使用者不存在或密碼錯誤
        return response()->json(['error' => '用戶不存在或密碼錯誤'], 401);
    }

    // 重發驗證信
    public function resendverification(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // 根據用戶名查找用戶
        $user = User::where('username', $username)->first();

        // 檢查用戶是否存在以及密碼是否正確
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            // 用戶不存在或密碼不正確
            return response()->json(['error' => '用戶名或密碼不正確。'], 401);
        } else {
            // 傳送認證信
            Mail::to($user->email)->send(new SendVerifyMail($user, $user->verification_token));

            return response()->json(['success' => '驗證信已重發']);
        }
    }
}
