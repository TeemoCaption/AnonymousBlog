<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;  // 用於定義需要電子郵件驗證的使用者模型

use App\Mail\SendVerifyMail;  // 自定義的信件樣式、內容
use Illuminate\Support\Facades\Mail;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'username',
        'password',
        'verified',
        'verification_token',
    ];

    /**
     * 發送電子郵件驗證通知。
     */
    public function sendEmailVerificationNotification()
    {
        // 取得目前使用者的驗證 token
        $verificationToken = $this->verification_token;
        // 向 SendVerifyMail 建構函式傳遞目前使用者實例和驗證 token
        Mail::to($this->email)->send(new SendVerifyMail($this, $verificationToken));
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        //'password' => 'hashed',
    ];
}
