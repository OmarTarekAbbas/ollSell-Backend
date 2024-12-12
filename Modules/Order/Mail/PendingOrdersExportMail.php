<?php

namespace Modules\Order\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PendingOrdersExportMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $excelData;
    protected $fileName;

    public function __construct($excelData, $fileName)
    {
        $this->excelData = $excelData;
        $this->fileName = $fileName;
    }

    public function build()
    {
        return $this->view('emails.pending_orders_export')
            ->attachData(
                $this->excelData,
                $this->fileName,
                [
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
    }
}
