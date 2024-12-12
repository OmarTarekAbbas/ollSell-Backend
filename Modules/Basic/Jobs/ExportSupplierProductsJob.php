<?php

namespace Modules\Basic\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Modules\Basic\Entities\JobTracking;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\MasterCatalog\Exports\Supplier\ProductExportBySupplier;
use Modules\CoreData\Actions\Notification\SendNotificationForSupplierAction;
//todo change
class ExportSupplierProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;

    protected $request;
    protected $token;

    public function __construct($request, $token)
    {
        $this->request = $request;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Generate a unique file name using a timestamp
        $timestamp = Carbon::now()->timestamp;
        $filePath = 'exports/exportProducts_' . $timestamp . '.xlsx';
        
        $tracking = JobTracking::where('token', $this->token)->first();

        // Generate Excel file
        Excel::store(new ProductExportBySupplier($this->request['search'], $tracking->user_id),  $filePath);

        $title = json_encode([
            'en' => 'Export Completed',
            'ar' => 'اكتمل التصدير',
        ]);

        $content = json_encode([
            'en' => 'Your export is ready! Click to download.',
            'ar' => 'التصدير جاهز! انقر أدناه لتنزيل طلباتك.',
        ]);

        $urlType = 'export';
        $urlId = null;
        $color = '#1E90FF';


        App(SendNotificationForSupplierAction::class)->execute($title, $content, $tracking->user_id, $urlType, $urlId, $color, $filePath);

        $tracking->delete();
    }
}
