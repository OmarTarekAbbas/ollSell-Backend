<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendHourlyOllopsRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-hourly-ollops-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a request to the specified endpoint every hour';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = config('services.ollops.base_url') . '/order/test?appId=' . config('OLLOPS_APP_ID');

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $this->info('Request sent successfully.');
            } else {
                $this->error('Request failed: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('Error sending request: ' . $e->getMessage());
        }

        return 0;
    }
}
