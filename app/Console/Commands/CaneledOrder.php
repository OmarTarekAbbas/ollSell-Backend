<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Services\AymakanService;
use Illuminate\Support\Facades\DB;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderItem;
use Modules\Order\Enums\OrderEnum;
use Illuminate\Support\Facades\Http;

class CaneledOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:canceled-order';

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
        $orders = Order::whereIN('id', [29550,
            29572,
            29593,
            29610,
            29631,
            29653,
            32392,
            32395,
            32396,
            33069,
            33068,
            32398,
            32399,
            32400,
            32402,
            33062,
            33061,
            32411,
            32413,
            32418,
            32419,
            33056,
            32420,
            32421,
            32423,
            32425,
            32433,
            32434,
            32438,
            32451,
            32459,
            32461,
            32462,
            33044,
            32468,
            33042,
            32472,
            32476,
            32478,
            32481,
            32485,
            33036,
            32490,
            32501,
            33030,
            32505,
            33027,
            32509,
            32513,
            32517,
            32519,
            32520,
            33019,
            32522,
            32529,
            32533,
            32534,
            32537,
            33013,
            32542,
            33011,
            33010,
            32551,
            32553,
            32556,
            33006,
            32559,
            32562,
            33003,
            32564,
            32565,
            32568,
            32569,
            32998,
            32579,
            32581,
            32582,
            32583,
            32589,
            32590,
            32990,
            32989,
            32591,
            32597,
            32600,
            32985,
            32601,
            32602,
            32606,
            32613,
            32980,
            32614,
            32615,
            32977,
            32976,
            32618,
            32621,
            32625,
            32972,
            32631,
            32634,
            32637,
            32968,
            32639,
            32640,
            32965,
            32964,
            32641,
            32962,
            32961,
            32960,
            32959,
            32645,
            32650,
            32956,
            32655,
            32658,
            32953,
            32952,
            32661,
            32662,
            32664,
            32672,
            32674,
            32946,
            32678,
            32681,
            32685,
            32942,
            32941,
            32940,
            32687,
            32692,
            32937,
            32694,
            32696,
            32934,
            32702,
            32704,
            32931,
            32930,
            32705,
            32708,
            32927,
            32710,
            32716,
            32718,
            32720,
            32921,
            32920,
            32725,
            32918,
            32730,
            32741,
            32743,
            32746,
            32749,
            32751,
            32911,
            32754,
            32756,
            32908,
            32761,
            32906,
            32764,
            32773,
            32776,
            32779,
            32900,
            32898,
            32897,
            32896,
            32894,
            32780,
            32783,
            32890,
            32785,
            32888,
            32887,
            32786,
            32885,
            32884,
            32789,
            32793,
            32795,
            32879,
            32877,
            32876,
            32796,
            32797,
            32800,
            32872,
            32871,
            32801,
            32804,
            32867,
            32866,
            32865,
            32864,
            32863,
            32809,
            32811,
            32812,
            32814,
            32816,
            32823,
            32856,
            32824,
            32826,
            32827,
            32834,
            32836,
            32842,
            32843,
            32844,
            32846,
            32845
        ])->get();
        foreach ($orders as $order) {
            $this->info($order->id . ' - start');
           /* $trackShipment = Http::withHeader('Accept', 'application/json')
                ->withHeader(
                    'Authorization',
                    '767278fb55db48b2620dc3bbde89b765-5c828234-3ce7-4987-9ec2-4cccc7817642-5de6ddf1a3d10d3b941f4134380b31da/657b12835fe346a0f950b0fcb06e0a11/51dbdd4d-5532-443a-950f-3a9664d743e5'
                )
                ->post('https://api.aymakan.net/v2/shipping/cancel', ['tracking' => $order->tracking_number]);
            $this->info($order->id . ' - ' . $trackShipment['message']);
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                'https://prodapi.omniful.com/sales-channel/public/v1/orders/' . $order->id . '/cancel'
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            $data = json_encode([
                "cancel_reason" => "out stock"
            ]);
            // Attach the JSON data
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // Set HTTP Headers for request
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3MzIxMzY0NjEsImp0aSI6ImM1M2RhZDMyLTU2YzEtNDEwYi04OGQ5LTU0ZGRlNGRhNTUzZSIsInVzZXJfZGV0YWlscyI6eyJTZWxsZXJTYWxlc0NoYW5uZWxJRCI6MzE4LCJTZWxsZXJJRCI6IjIxMSIsIlRlbmFudElEIjoiMSIsIlVzZXJJRCI6IjM4MSIsIlVzZXJOYW1lIjoiTW9oYW1tYWQgVGFsaGEifSwidG9rZW5fdHlwZSI6IkJlYXJlciJ9.MBljhmRny-xELeAEfksBWIh_5RpdDlDvw1SrnpV2ZM2gHTPDk3LfBv0jpae_i1NA019X6aC1-3lOwIMFULhEPXD-BOWcZdkwBPcZSMXQ7FZejj_Sb2L6WDCDbpLbyBbnrOzp9MGGBK2BQGWBsdBdbZKp7A25BmjCEUMnoyVVZoYRlcizlLkEcFJBflGFX35MM2hdp6kOCuZdpFyo4k8ujkD1aY1elt4J4xpDsYjwuDsquMO-QZcN6ooWtUni7LxZlHCnHrAbZuVgXUS26Rtf6DChoBUZQF36RyalTCUNjVgwwLASy5q2QPhTmDcOSfkJlS11SEA1TN8p9jGU1DrlMmk96M9NVeR5E8mpfQXRdzeXtRHmbf8xEWvA1TQ2yp5ipBiup_Nr1s1xKTRplDuu-yW1psVNoj21uWGsJGLassJoqMzhavhb_gW39u2SGv7UCpzKlQ6tju7ZbLHZ0dUGPKQQ-VZTC0M00dFwHP5qto_2-Z2Rb44A2gYqp_CVwYb-TJYznUfyJfNWh2Csr8qRsrCE6Op7cZmjJHut2UseDJt3mOzLYe1oMnYIy2nmzkKpl34QETji8Pm73SVWeEPvlwCgyzFCQvoI9XLWsjW9xT4etuDdJ1chK2dctkDbztnRhWPDE3-O0VsLGLoMcFFHsPUwFhO--VZ_LjbxY-0t6yY',
                'Content-Length: ' . strlen($data)
            ]);
            // Execute cURL request and get the response
            $response = curl_exec($ch);
            // Check if the request was successful
            if (curl_errno($ch)) {
                $this->info($order->id . ' - ' . curl_error($ch));
            } else {
                // Decode the JSON response into a PHP array
                $responseData = json_decode($response, true);
                if ($responseData['is_success'] && $responseData['status_code'] == 200) {
                    // Successful order cancellation
                    $this->info($order->id . ' - ' . $responseData['data']['status']);
                } else {
                    // Handle error
                    $this->info($order->id . ' - Failed to cancel order');
                }
            }
            curl_close($ch);*/
            $order->update([ 'status_id'=>OrderEnum::CANCELED_STATUS , 'cancelDate' =>now(),'sub_status_id'=>2,'remark_id'=>153 ]);
            $this->info($order->id . ' - end');
        }
    }
}
