<?php

namespace Modules\Order\Jobs;

use Illuminate\Bus\Queueable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Order\Imports\UpdateOrdersImport;

class ProcessOrderImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Handle the import process
        try {
            $import = new UpdateOrdersImport();
            // Excel::import($import, $this->filePath);

            Excel::import($import, $this->filePath);

        } catch (\Exception $e) {
            // Log or handle any errors that occur during the import process
            info('Error importing orders: ' . $e->getMessage());
        } finally {
            // Delete the file after processing
            Storage::delete($this->filePath);
        }
    }
}
