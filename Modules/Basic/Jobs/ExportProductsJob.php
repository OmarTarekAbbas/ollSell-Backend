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
use Modules\MasterCatalog\Exports\Admin\ProductExportByAdmin;
use Modules\CoreData\Actions\Notification\SendNotificationByAdminAction;
//todo change
class ExportProductsJob implements ShouldQueue
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
        JobTracking::where('token', $this->token)->delete();
        // Generate Excel file
        Excel::store(new ProductExportByAdmin($this->request),  $filePath);

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
        $admin_id = $this->request['admin_id'];
        
        App(SendNotificationByAdminAction::class)->execute($title, $content, $urlType, $urlId, $color, $filePath, $admin_id);


    }
}
