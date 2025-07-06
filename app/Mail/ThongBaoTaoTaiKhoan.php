<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ThongBaoTaoTaiKhoan extends Mailable
{
    use Queueable, SerializesModels;

    public $account;
    public $plainPassword;

    /**
     * Create a new message instance.
     */
    public function __construct($account, $plainPassword)
    {
        $this->account = $account;
        $this->plainPassword = $plainPassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thông báo tài khoản đã được tạo',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.thongbao',
            with: [
                'account' => $this->account,
                'plainPassword' => $this->plainPassword,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
