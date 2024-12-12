<?php

namespace Modules\Order\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Exports\Order\Admin\OrderInsightsExport;
use Modules\Order\Exports\Order\Admin\OrderInsightsExportSheet;

class ExportOrdersReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filePath;
    public $orders;
    public $user;

    public function __construct($orders,$filePath, $user)
    {
        $this->filePath = $filePath;
        $this->orders = $orders;
        $this->user = $user;
    }

    public function handle()
    {
        Excel::store(new OrderInsightsExport($this->orders), $this->filePath, 'public');

        // send mail
        $this->sendEmailWithAttachment($this->filePath);
    }

    protected function sendEmailWithAttachment($filePath)
    {
        $fileUrl = Storage::disk('public')->url($filePath);

        Mail::to($this->user->email)->send(new OrderInsightsExportSheet($fileUrl, $filePath));
    }
}


