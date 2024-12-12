<?php

namespace Modules\Order\Exports\Order\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderInsightsExportSheet extends Mailable
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
        return $this->subject('Your Report Export is Ready')
            ->view('emails.insightsExported')
            ->attachFromStorageDisk('public', $this->filePath, 'insights.xlsx')
            ->with([
                'fileUrl' => $this->fileUrl,
            ]);
    }
}
