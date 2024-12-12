<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AymakanService
{
    protected $httpClient;
    protected $baseUrl;

    public function __construct()
    {
        $this->httpClient = new Client();
        $this->baseUrl = config('services.aymakan.base_url');
    }

    public  function fetchSKU($SKU)
    {
        try {
            $response = $this->httpClient->get($this->baseUrl . 'inventory/skus/' . $SKU, [
                'headers' => [
                    'api-key' => config('services.aymakan.environment') == 'dev' ? config('services.aymakan.dev_api_key') : config('services.aymakan.live_api_key'),
                    'Content-Type' => 'application/json',
                ]
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


    public  function fetchAllSKU($page)
    {
        try {
            $response = $this->httpClient->get($this->baseUrl . 'inventory?per_page=20&page=' . $page, [
                'headers' => [
                    'api-key' => config('services.aymakan.environment') == 'dev' ? config('services.aymakan.dev_api_key') : config('services.aymakan.live_api_key'),
                    'Content-Type' => 'application/json',
                ]
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

    public function sendOrderWMS($orderData)
    {
        try {
            $response = $this->httpClient->post($this->baseUrl . 'orders', [
                'headers' => [
                    'api-key' => config('services.aymakan.environment') == 'dev' ? config('services.aymakan.dev_api_key') : config('services.aymakan.live_api_key'),
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

    public function getOrderWMS($id)
    {
        try {
            $response = $this->httpClient->get($this->baseUrl . 'orders/' . $id, [
                'headers' => [
                    'api-key' => config('services.aymakan.environment') == 'dev' ? config('services.aymakan.dev_api_key') : config('services.aymakan.live_api_key'),
                    'Content-Type' => 'application/json',
                ],
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

    /**
     * The function `sendRequest` sends an HTTP request with specified method, URL, and payload using
     * Guzzle HTTP client in PHP.
     * 
     * @param string method The `method` parameter in the `sendRequest` function represents the HTTP
     * method to be used for the request, such as 'GET', 'POST', 'PUT', 'DELETE', etc. It specifies the
     * type of action the request is intended to perform on the specified resource.
     * @param string url The `url` parameter in the `sendRequest` function is a string that represents
     * the URL to which the HTTP request will be sent. This URL typically points to a specific endpoint
     * on a server where the request will be processed.
     * @param array payload The `payload` parameter in the `sendRequest` function is an array that
     * contains the data you want to send in the request. This data will be converted to JSON format
     * and included in the request body when making the HTTP request.
     * 
     * @return string The `sendRequest` function returns a string, which is the contents of the
     * response body from the HTTP request made using the specified method, URL, and payload.
     */
    private function sendRequest(string $method, string $url, array $payload): string
    {
        $client = new Client();

        $response = $client->request($method, $url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'api-key' => config('services.aymakan.environment') == 'dev' ? config('services.aymakan.dev_api_key') : config('services.aymakan.live_api_key'),
            ],
            'json' => $payload,
        ]);
        return $response->getBody()->getContents();
    }
    /**
     * Sync product data with external API for updating an existing product.
     *
     * @param object $product
     * @return string
     */
    public function syncWithUpdateAPI($product)
    {
        return $this->sendRequest('PUT', $this->baseUrl . 'skus', $this->payload($product));
    }

    /**
     * Sync product data with external API for creating a new product.
     *
     * @param object $product
     * @return string
     */
    public function syncWithCreateAPI($product)
    {
        return $this->sendRequest('POST', $this->baseUrl . 'skus', $this->payload($product));
    }

    /**
     * The function "payload" returns an array with product information extracted from the given
     * object.
     * 
     * @param product The `payload` function takes a `` object as a parameter and returns an
     * array with specific keys and values based on the properties of the `` object.
     * 
     * @return 
     */
    public function payload($product)
    {
        return [
            [
                'name' => $product->name->value,
                'description' => $product->description->value,
                'sku_id' => $product->sku,
                'sku_code' => $product->sku,
                'handling_type' => 'cold',
                'type' => 'simple',
                'status' => 'live',
                'cost' => $product->cost_price,
                'retail_price' => $product->cost_price,
                'selling_price' => $product->cost_price,
                'product_url' => 'https://example.com/tv'
            ]
        ];
    }
}
