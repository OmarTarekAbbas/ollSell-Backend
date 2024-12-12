<?php

namespace Modules\Order\Actions\PendingOrder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Order\Mail\PendingOrdersExportMail;
use Modules\Order\Exports\Order\Dropshipper\PendingOrdersExport;
class ExportPendingOrdersAction
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        $user = Auth::user(); // Get the logged-in user

        // Generate the Excel file content
        $fileName = 'pending_orders_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $excelData = Excel::raw(new PendingOrdersExport(user: $user), \Maatwebsite\Excel\Excel::XLSX);

        // Send the email with the attachment
        Mail::to($user->email)->send(new PendingOrdersExportMail($excelData, $fileName));

        return true;
    }
}
