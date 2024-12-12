<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailAndPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * return void
     */
    private $email;
    private $password;

    public function __construct($email , $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     *
     * return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Send Password',
        );
    }

    /**
     * Get the message content definition.
     *
     * return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.emailandpassword',
            with: [
                'email' => $this->email,
                'password' => $this->password,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * return array
     */
    public function attachments()
    {
        return [];
    }
}