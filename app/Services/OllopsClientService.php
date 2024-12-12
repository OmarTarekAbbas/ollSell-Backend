<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class OllopsClientService
{
    protected $httpClient;
    protected $baseUrl;

    public function __construct()
    {
        $this->httpClient = new Client();
        $this->baseUrl = config('services.ollops.base_url') ?? 'https://beta-api.ollops.com';
    }

    public function sendOrder($orderData)
    {
        try {
            $response = $this->httpClient->post($this->baseUrl . '/order/create', [
                'headers' => [
                    'authkey' => config('services.ollops.environment') == 'dev' ? config('services.ollops.dev_api_key') : config('services.ollops.live_api_key'),
                    'Content-Type' => 'application/json',
                ],
                'json' => $orderData,
            ]);

            return $response; // Return the entire response object
        } catch (RequestException $e) {
            // Handle exceptions (e.g., API errors)
            if ($e->hasResponse()) {
                return $e->getResponse(); // Return the response object
            } else {
                return json_encode(['error' => 'Something went wrong']);
            }
        }
    }

    public function updateOrderStatus($statusData)
    {
        try {
            $response = $this->httpClient->post($this->baseUrl . '/order/update-status', [
                'headers' => [
                    'authkey' => config('services.ollops.environment') == 'dev' ? config('services.ollops.dev_api_key') : config('services.ollops.live_api_key'),
                    'Content-Type' => 'application/json',
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
    public function getValidatedByValue($payload)
    {
        try {
            $response = $this->httpClient->post($this->baseUrl . '/order/validated-by', [
                'headers' => [
                    'authkey' => config('services.ollops.environment') == 'dev' ? config('services.ollops.dev_api_key') : config('services.ollops.live_api_key'),
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            return $response; // Return the entire response object
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse(); // Return the response object
            } else {
                return json_encode(['error' => 'Something went wrong']);
            }
        }
    }

    // get message templates
    public function getMessageTemplates()
    {
        $authKey = config('services.ollops.environment') == 'dev' ? config('services.ollops.dev_api_key') : config('services.ollops.live_api_key');

        if(!$authKey) {
            $authKey = 'da483355dd11107435e95f58f194f1c859d2c71ee1bd26171438ac44d2bbf071d27f65c2907d7629f22034306d2d2777';
        }

        try {
            $response = $this->httpClient->get($this->baseUrl . '/order/message-templates', [
                'headers' => [
                    'authkey' => $authKey,
                    'Content-Type' => 'application/json',
                ],
            ]);

            return $response; // Return the entire response object
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse(); // Return the response object
            } else {
                return json_encode(['error' => 'Something went wrong']);
            }
        }
    }

    public function sendMessage($payload)
    {
        $authKey = config('services.ollops.environment') == 'dev' ? config('services.ollops.dev_api_key') : config('services.ollops.live_api_key');

        if(!$authKey) {
            $authKey = 'da483355dd11107435e95f58f194f1c859d2c71ee1bd26171438ac44d2bbf071d27f65c2907d7629f22034306d2d2777';
        }

        try {
            $response = $this->httpClient->post($this->baseUrl . '/order/process-orders', [
                'headers' => [
                    'authkey' => $authKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            return $response; // Return the entire response object
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse(); // Return the response object
            } else {
                return json_encode(['error' => 'Something went wrong']);
            }
        }
    }
}
