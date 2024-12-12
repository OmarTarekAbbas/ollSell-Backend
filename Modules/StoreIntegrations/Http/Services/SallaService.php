<?php

namespace Modules\StoreIntegrations\Http\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Salla\OAuth2\Client\Provider\Salla;
use GuzzleHttp\Exception\RequestException;
use Modules\StoreIntegrations\Entities\SallaToken;

class SallaService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new Salla([
            'clientId'     => config('salla.client_id'),
            'clientSecret' => config('salla.client_secret'),
            'redirectUri'  => config('salla.redirect_uri'),
        ]);
    }

    public function getBaseUrl()
    {
        return config('salla.base_url');
    }

    public function getAuthorizationUrl()
    {
        return $this->provider->getAuthorizationUrl([
            'scope' => 'offline_access',
        ]);
    }

    public function handleAccessToken($code, $dropshipperId)
    {
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);

        $sallaUser = $this->provider->getResourceOwner($token);
        // dd($sallaUser);
        $merchantId = $sallaUser['merchant']['id'];
        $storeName = $sallaUser['merchant']['name'];
        $storeDomain = $sallaUser['merchant']['domain'];

        SallaToken::updateOrCreate(
            ['merchant_id' => $merchantId],
            [
                'dropshipper_id' => $dropshipperId,
                'access_token' => $token->getToken(),
                'refresh_token' => $token->getRefreshToken(),
                'expires_at' => now()->addSeconds($token->getExpires() - time()),
                'store_name' => $storeName,
                'store_domain' => $storeDomain,
            ]
        );
    }

    public function refreshAccessToken(SallaToken $sallaToken)
    {
        $newToken = $this->provider->getAccessToken('refresh_token', [
            'refresh_token' => $sallaToken->refresh_token,
        ]);

        $sallaToken->refreshToken(
            $newToken->getToken(),
            $newToken->getRefreshToken(),
            $newToken->getExpires() - time()
        );

        return $newToken;
    }

    public function fetchOrders($merchantId)
    {
        $sallaToken = SallaToken::where('merchant_id', $merchantId)->first();

        if ($sallaToken->isExpired()) {
            $newToken = $this->provider->getAccessToken('refresh_token', [
                'refresh_token' => $sallaToken->refresh_token,
            ]);

            $sallaToken->refreshToken(
                $newToken->getToken(),
                $newToken->getRefreshToken(),
                $newToken->getExpires() - time()
            );
        }

        return $this->provider->fetchResource(
            'GET',
            config('salla.base_url') . '/orders',
            $sallaToken->access_token
        );
    }

    public function updateOrderStatus($statusData, $dropshipper, $order_id)
    {
        try {
            $httpClient = new Client();

            $response = $httpClient->post("https://api.salla.dev/admin/v2/orders/$order_id/status", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $dropshipper->access_token
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


    public function updateOrderShipment($statusData, $dropshipper, $order_id)
    {
        try {
            $httpClient = new Client();
            $response = $httpClient->put("https://api.salla.dev/admin/v2/orders/$order_id/update-shipment", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $dropshipper->access_token
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
}
