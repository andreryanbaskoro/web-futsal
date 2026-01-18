<?php

namespace App\Mail;

use App\Models\Pengguna;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Pengguna $user
     */
    public function __construct(Pengguna $user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verifikasi Email Anda di Futsal ACR',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'auth.verify-email', // Pastikan menggunakan tampilan 'auth.verify-email'
            with: [
                'verification_code' => $this->user->verification_code, // Kode verifikasi
                'name' => $this->user->nama, // Nama pengguna
                'email' => $this->user->email, // Email pengguna
                'instructions' => 'Masukkan kode verifikasi berikut pada halaman verifikasi di situs kami.'
            ]
        );
    }


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
