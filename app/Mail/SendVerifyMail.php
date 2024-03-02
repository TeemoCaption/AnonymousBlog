<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class SendVerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
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
    public function content(): Content  // 信件內容
    {
        return new Content(
            view: 'emailVerify',
            with: [  // 將數據傳遞給視圖
                'user' => $this->user,
                'id' => $this->user->id, // 確保這裡傳遞了 id
                'hash' => sha1($this->user->email), // 確保這裡傳遞了 hash
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
