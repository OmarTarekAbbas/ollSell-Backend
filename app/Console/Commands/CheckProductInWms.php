<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AymakanService;
use Illuminate\Support\Facades\DB;


class CheckProductInWms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-product-in-wms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Product In Wms';

    /**
     * Execute the console command.
     */
    public function handle()
    {


        $aymakan = new  AymakanService();
        $products = DB::table('products')->where('is_wms', 0)->get();
        foreach ($products as $row) {
                $this->info('successfully!' . $row->sku);

            $response = $aymakan->fetchSKU($row->sku);
            if (isset($response)) {
                $body = $response->getBody()->getContents();
                $statusCode = $response->getStatusCode();
                $responseData = json_decode($body, true);

                if ($statusCode == 200) {
                    $sku = $responseData['data']['sku_code'] ?? null;
                    if ($sku) {
                        $this->info('update!'.$row->sku);
                        DB::table('products')
                            ->where('id', $row->id)
                            ->update([
                                'is_wms' => 1
                            ]);
                    }
                }
            }

        }
    }
}
