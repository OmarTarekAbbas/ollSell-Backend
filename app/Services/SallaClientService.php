<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SallaClientService
{
    protected $httpClient;
    protected $baseUrl;

    public function __construct()
    {
        $this->httpClient = new Client();
        $this->baseUrl = config('services.salla.base_url');
    }


    public function updateOrderStatus($statusData,$dropshipper,$order_id)
    {
        try {
            $response = $this->httpClient->post($this->baseUrl . "/orders/$order_id/status", [
                'headers' => [
                    'Accept' => 'application/json',
                   'Authorization' => 'Bearer '.$dropshipper->access_token
                ],
                'json' => $statusData,
            ]);

            return $response;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse();
            } else {
                return json_encode(['error' => 'Something went wrong']);
            }
        }
    }



    
public function getOrders($dropshipper){

    $client = new Client();
  $headers = [
      'Accept' => 'application/json',
     'Authorization' => 'Bearer '.$dropshipper->access_token
    ];
    try {
        $response = $client->get('https://api.salla.dev/admin/v2/orders?payment_method[]=cod&from_date=2024-09-09', [
            'headers' => $headers 
        ]);

        return  $response; // Return the entire response object
    } catch (RequestException $e) {
      
        // Handle exceptions (e.g., API errors)
        if ($e->hasResponse()) {
            return $e->getResponse(); // Return the response object
        } else {
            return json_encode(['error' => 'Something went wrong']);
        }
    }
}

public function getOrder($id,$dropshipper){

    $client = new Client();
  $headers = [
      'Accept' => 'application/json',
     'Authorization' => 'Bearer '.$dropshipper->access_token
    ];
    try {
        $response = $client->get("https://api.salla.dev/admin/v2/orders/$id", [
            'headers' => $headers 
        ]);

        return  $response->getBody()->getContents(); // Return the entire response object
    } catch (RequestException $e) {
      
        // Handle exceptions (e.g., API errors)
        if ($e->hasResponse()) {
            return $e->getResponse(); // Return the response object
        } else {
            return json_encode(['error' => 'Something went wrong']);
        }
    }
}
   

}
