<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
//todo change
class SendVerificationCode extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * return void
     */
    private $name;
    private $code;

    public function __construct($name , $code)
    {
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * Get the message envelope.
     *
     * return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Send Verification Code',
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
            markdown: 'emails.verification',
            with: [
                'name' => $this->name,
                'code' => $this->code,
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
