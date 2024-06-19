<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class EmailVerificationReminder extends Mailable
{
    use Queueable, SerializesModels;

    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify', now()->addDays(3), [
                'id' => $this->user->id,
                'hash' => sha1($this->user->email),
            ]
        );

        $deleteAccountUrl = URL::signedRoute('account.delete', [
            'id' => $this->user->id,
        ]
        );

        return $this->markdown('mail.email_verification_reminder')
            ->subject('Email Verification Reminder')
            ->with([
                'user' => $this->user,
                'verificationUrl' => $verificationUrl,
                'deleteAccountUrl' => $deleteAccountUrl,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Verification Reminder',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.email_verification_reminder',
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
