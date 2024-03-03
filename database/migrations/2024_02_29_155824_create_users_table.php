<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // 自動遞增ID
            $table->string('email')->unique(); // 電子信箱，設定為唯一
            $table->string('username'); // 用戶名
            $table->string('password'); // 密碼
            $table->boolean('verified')->default(false); // 驗證狀態，默認為false
            $table->string('verification_token')->nullable();  // 驗證token(可為null)
            $table->timestamps(); // 自動創建的created_at和updated_at時間戳
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
