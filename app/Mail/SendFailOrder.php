<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
//todo change
class SendFailOrder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * return void
     */
    private $failorders;


    public function __construct($failorders)
    {
        $this->failorders = $failorders;
    
    }

    /**
     * Get the message envelope.
     *
     * return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Send  Fail orders Listing',
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
            markdown: 'emails.fail_orders',
            with: [
                'failorders' => $this->failorders
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
