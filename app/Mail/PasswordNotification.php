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
    public $identifier;
    public $identifierLabel;
    public $role;
    public $isReset;

    /**
     * Create a new message instance.
     *
     * @param string $namaLengkap Nama lengkap user
     * @param string $email Email user
     * @param string $password Password yang digenerate
     * @param string $role Role user (pasien, dokter, staf)
     * @param string|null $identifier No Rekam Medis (pasien) atau NIP RS (dokter/staf)
     * @param bool $isReset Apakah ini reset password atau akun baru
     */
    public function __construct(string $namaLengkap, string $email, string $password, string $role, ?string $identifier = null, bool $isReset = false)
    {
        $this->namaLengkap = $namaLengkap;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->identifier = $identifier;
        $this->isReset = $isReset;

        // Set label berdasarkan role
        $this->identifierLabel = match ($role) {
            'pasien' => 'No. Rekam Medis',
            'dokter' => 'NIP RS',
            'staf' => 'NIP RS',
            default => 'ID'
        };
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if ($this->isReset) {
            $subject = 'Reset Password - Ganesha Hospital';
        } else {
            $subject = match ($this->role) {
                'pasien' => 'Informasi Akun Pasien - Ganesha Hospital',
                'dokter' => 'Informasi Akun Dokter - Ganesha Hospital',
                'staf' => 'Informasi Akun Staff - Ganesha Hospital',
                default => 'Informasi Akun - Ganesha Hospital'
            };
        }

        return new Envelope(
            subject: $subject,
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
