<?php

namespace Modules\StoreIntegrations\Http\Services;

use Modules\StoreIntegrations\Entities\SallaToken;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SallaProductSyncService
{
    protected $client;
    protected $sallaService;

    public function __construct(SallaService $sallaService)
    {
        $this->client = new Client();
        $this->sallaService = $sallaService;
    }

    public function createOrUpdateProduct($productData, $accessToken, $productId = null)
    {
        // Fetch the Salla token and refresh if necessary
        $sallaToken = SallaToken::where('access_token', $accessToken)->first();

        if ($sallaToken->isExpired()) {
            $newToken = $this->sallaService->refreshAccessToken($sallaToken);
            $accessToken = $newToken->getToken();
        } else {
            $accessToken = $sallaToken->access_token;
        }

        $url = $productId
            ? "https://api.salla.dev/admin/v2/products/{$productId}"
            : "https://api.salla.dev/admin/v2/products";

        $method = $productId ? 'PUT' : 'POST';

        $headers = [
            'User-Agent' => 'Apidog/1.0.0 (https://apidog.com)',
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json',
        ];

        Log::channel('salla')->info('Using access token:', ['token' => $accessToken]);

        try {
            $response = $this->client->request($method, $url, [
                'body' => json_encode($productData),
                'headers' => $headers,
            ]);
            Log::channel('salla')->info('Response', ['body' => $response->getBody()->getContents()]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::channel('salla')->error('Error during request', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    public function mapProductVariations($sallaProduct, $product)
    {
        // TODO when variations are ready to go
    }
}
