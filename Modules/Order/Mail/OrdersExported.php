<?php

namespace Modules\Order\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrdersExported extends Mailable
{
    use Queueable, SerializesModels;

    public $fileUrl;
    public $filePath;

    /**
     * Create a new message instance.
     */
    public function __construct($fileUrl, $filePath)
    {
        $this->fileUrl = $fileUrl;
        $this->filePath = $filePath;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Orders Export is Ready')
            ->view('emails.ordersExported')
            ->attachFromStorageDisk('public', $this->filePath, 'orders.csv')
            ->with([
                'fileUrl' => $this->fileUrl,
            ]);
    }
}
