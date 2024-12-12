<?php

namespace Modules\Order\Actions\Order;

use Illuminate\Support\Facades\Http;


class CancelledIntegrationOrderAction
{


    /**
     * This function executes an order by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the order data.
     *
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     *
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute($order)
    {
        $trackShipment = Http::withHeader('Accept', 'application/json')
            ->withHeader('Authorization',setting('AYMAKAN_SECRET_API_KEY'))
            ->post(setting('AYMAKAN_API_URL').'/shipping/cancel', ['tracking' => $order->tracking_number]);
        if(!isset($trackShipment['success']) || !$trackShipment['success'])
        {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,
            setting('WMS_BASE_URL').'/sales-channel/public/v1/orders/' . $order->id . '/cancel');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        $data = json_encode([
            "cancel_reason" => "canelled by customer"
        ]);
        // Attach the JSON data
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // Set HTTP Headers for request
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.setting('WMS_SECRET_KEY'),
            'Content-Length: ' . strlen($data)
        ]);
        // Execute cURL request and get the response
        $response = curl_exec($ch);
        // Check if the request was successful
        if(curl_errno($ch))
        {
            $this->info($order->id . ' - ' . curl_error($ch));
            return false;
        }else
        {
            // Decode the JSON response into a PHP array
            $responseData = json_decode($response, true);
            if($responseData['is_success'] && $responseData['status_code'] == 200)
            {
                // Successful order cancellation
                return true;
            }else
            {
                return false;
            }
        }
        curl_close($ch);
        return false;
    }
}
