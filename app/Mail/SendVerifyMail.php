<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\URL;

use App\Models\User;

class SendVerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $user;
    public $verificationToken; // 認證信驗證Token

    public function __construct(User $user, $verificationToken)
    {
        $this->user = $user;
        $this->verificationToken = $verificationToken;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope  // 信件主題
    {
        return new Envelope(
            subject: '匿名交友網站認證信',
        );
    }

    /**
     * Get the message content definition.
     */

    public function content(): Content // 信件內容
    {
        // 產生帶有驗證 token 的驗證連結
        $verificationUrl = route('verification.verify', [
            'id' => $this->user->id,
            'hash' => sha1($this->user->email),
            'token' => $this->user->verification_token // 使用使用者的驗證 token
        ]);

        return new Content(
            view: 'emailVerify',
            with: [ // 將資料傳遞給視圖
                'user' => $this->user,
                'verificationUrl' => $verificationUrl, // 傳遞帶有驗證 token 的驗證鏈接
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array  // 信件內附件內容設定
    {
        return [];
    }
}
