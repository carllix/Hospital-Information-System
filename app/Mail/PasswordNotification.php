<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $namaLengkap;
    public $email;
    public $password;
    public $noRekamMedis;

    /**
     * Create a new message instance.
     */
    public function __construct(string $namaLengkap, string $email, string $password, ?string $noRekamMedis = null)
    {
        $this->namaLengkap = $namaLengkap;
        $this->email = $email;
        $this->password = $password;
        $this->noRekamMedis = $noRekamMedis;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informasi Akun Pasien - Rumah Sakit',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password-notification',
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
