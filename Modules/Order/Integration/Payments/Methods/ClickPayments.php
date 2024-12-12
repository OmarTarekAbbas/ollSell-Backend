<?php

namespace Modules\Order\Integration\Payments\Methods;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Modules\Order\Enums\ClickPayEnum;
use Modules\Order\Integration\Models\ServiceLog;
use GuzzleHttp\Exception\BadResponseException;
use Modules\Order\Integration\Models\CardRegistration;
use Modules\Order\Integration\Contracts\PaymentGatewayResponse;
use Modules\Order\Integration\Contracts\PaymentMethodInterface;
use Modules\Order\Integration\Models\ClickPaymentLog;
use Modules\Order\Integration\Responses\Payments\ClickPaymentsResponse;

class ClickPayments implements PaymentMethodInterface
{
    /**
     * Payment Settings
     *
     * @var array
     */
    private $settings = [];

    /**
     * Request response
     *
     * @var Response
     */
    private $response;

    /**
     * Constructor
     */
    public function __construct()
    {
        $settings = config('services.payments.ClickPayments');
        if ($settings['mode'] === 'LIVE') {
            $mode = $settings['data']['live'];
        } else {
            $mode = $settings['data']['sandbox'];
        }

        unset($settings['data']);

        $this->settings = array_merge($settings, $mode);
    }

    /**
     * Generate payment url
     *
     * @param int|string $orderId
     * @param $amount
     * @param $paymentMethod
     * @param null $userInfo
     * @return string hyper checkout id
     * @throws \Exception
     */
    public function initiate($order, $amount, $paymentMethod, $userInfo = null)
    {
        $curl = curl_init();


        curl_setopt_array($curl, array(
            CURLOPT_URL => setting('CLICKPAY_ENDPOINT') ?? env('CLICKPAY_LIVE_ENDPOINT'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($this->data($order, $amount)),
            CURLOPT_HTTPHEADER => array(
                'authorization:' . $this->settings['serverKey'],
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $jsonResponse = json_decode($response, true);


        $clickPaymentLog = new ClickPaymentLog();
        $clickPaymentLog->data = $response;
        $clickPaymentLog->order_id = $order->id;
        if(isset($jsonResponse['code']) && $jsonResponse['code'] == 4)
        {
            $clickPaymentLog->status = ClickPayEnum::Duplicate;
        }else{
            $clickPaymentLog->status = ClickPayEnum::Open;
        }
        $clickPaymentLog->save();
        return $jsonResponse;
    }

    /**
     * Get access token based on current environment
     *
     * @return string
     */
    private function getAccessToken(): string
    {
        $mode = ucwords(strtolower($this->option('mode')));
        $accessToken = $this->option('businessId') . '.' . $this->option('appName') . ':' . $this->option('appKey');
        // dd($accessToken);
        $accessToken = base64_encode($accessToken);

        return "Key_{$mode} {$accessToken}";
        // return "Key_Test ZGlldF9tYXJrZXQuNTZlZWUxZWM0YWM3NDM2OTk0N2ZhNjFhNzY5YTM4NTA6OWQ1ZjllZjZjN2Q5NDJhNTgxZDBiZWMzYjQ3ODAxYmM=";
    }

    /**
     * Check if current status of payment is sandbox mode
     *
     * @return bool
     */
    private function isSandboxMode(): bool
    {
        return !$this->isLiveMode();
    }

    /**
     * Check if current status of payment is sandbox mode
     *
     * @return bool
     */
    private function isLiveMode(): bool
    {
        return $this->option("mode") === 'LIVE';
    }

    /**
     * Get payment response status
     *
     * @param int $orderId
     * @param string $checkOutId
     * @param string $paymentMethod
     * @return PaymentGatewayResponse
     */
    public function confirm(int $orderId, string $checkOutId, string $paymentMethod): PaymentGatewayResponse
    {
        $paymentMethod = strtoupper($paymentMethod);
        $options = [
            "apiOperation" => "SALE",
            "order" => [
                "Id" => "{$checkOutId}",
            ],
        ];

        $content = $this->send($route = "/order", $options);
        $responseStatusCode = $this->response->getStatusCode();

        $responseData = [
            'response' => $content,
            'statusCode' => $content->result->order->status ?? ClickPaymentsResponse::FAILED,
            'message' => $content->message,
            'responseStatusCode' => $responseStatusCode,
        ];

        $response = new ClickPaymentsResponse($responseData);

        $this->log([
            'route' => $route,
            'paymentMethod' => $paymentMethod,
            'orderId' => $orderId,
            'request' => $options,
            'response' => $content,
            'noonPaymentId' => $checkOutId,
            'channel' => 'paymentStatus',
            'responseCode' => $this->response->getStatusCode(),
        ]);

        if ($response->isCompleted() && (!empty($response->getResponse()->result->paymentDetails->tokenIdentifier)) && ($registrationId = $response->getResponse()->result->paymentDetails->tokenIdentifier) && !CardRegistration::where('registrationId', $registrationId)->exists()) {
            $user = user();

            if (!$user) {
                $order = repo('orders')->get((int) $orderId);
                $user = repo('customers')->sharedinfo((int) $order['customer']['id']);
            }

            $paymentDetails = $response->getResponse()->result->paymentDetails;

            CardRegistration::create([
                'registrationId' => $registrationId,
                'card' => $response->getResponse()->result->paymentDetails->paymentInfo,
                'createdBy' => $user,
                'paymentBrand' => $paymentMethod,
                'default' => false,

                "instrument" => $paymentDetails->instrument,
                "tokenIdentifier" => $paymentDetails->tokenIdentifier,
                "cardAlias" => $paymentDetails->cardAlias,
                "mode" => $paymentDetails->mode,
                "integratorAccount" => $paymentDetails->integratorAccount,
                "paymentInfo" => $paymentDetails->paymentInfo,
                "paymentMechanism" => $paymentDetails->paymentMechanism,
                "payerInfo" => $paymentDetails->payerInfo,
                "brand" => $paymentDetails->brand,
                "scheme" => $paymentDetails->scheme,
                "expiryMonth" => $paymentDetails->expiryMonth,
                "expiryYear" => $paymentDetails->expiryYear,
                "isNetworkToken" => $paymentDetails->isNetworkToken,
                "cardType" => $paymentDetails->cardType,
                "cardCountry" => $paymentDetails->cardCountry,
                "cardCountryName" => $paymentDetails->cardCountryName,
            ]);
        }

        return $response;
    }

    /**
     * Log the given data
     *
     * @param array $data
     * @return void
     */
    private function log(array $data)
    {
        $data = array_merge($data, [
            'type' => 'payment',
            'gateway' => 'noonPayments',
            'settings' => $this->settings,
            'userAgent' => request()->userAgent(),
        ]);

        $mapData = function ($data) use (&$mapData) {
            $details = [];

            foreach ($data as $key => $value) {
                $details[Str::camel(str_replace('.', '_', $key))] = is_array($value) || is_object($value) ? $mapData((array) $value) : $value;
            }

            return $details;
        };

        $details = $mapData($data);

        // PaymentLog::create($details);

        ServiceLog::create($details);
    }

    /**
     * Send the given request
     *
     * @param string $route
     * @param array $options
     * @param string $requestMethod
     * @return array|object
     */
    private function send(string $route, array $options, string $requestMethod = 'POST')
    {
        $requestMethod = strtolower($requestMethod);
        $client = new Client([
            'http_errors' => true,
            'headers' => [
                "Authorization" => $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ],
        ]);

        try {
            $this->response = $client->{$requestMethod}(rtrim($this->option('url'), '/') . $route, ['body' => json_encode($options)]);
        } catch (BadResponseException  $e) {
            $this->response = $e->getResponse();
        }

        return json_decode($this->response->getBody()->getContents());
    }

    /**
     * Get settings value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    private function option(string $key, $default = null)
    {
        return Arr::get($this->settings, $key, $default);
    }

    /**
     * Log the given data
     *
     * @param array $data
     * @return void
     */
    public function paymentLog(array $data)
    {
        $mapData = function ($data) use (&$mapData) {
            $details = [];

            foreach ($data as $key => $value) {
                $details[Str::camel(str_replace('.', '_', $key))] = is_array($value) || is_object($value) ? $mapData((array) $value) : $value;
            }

            return $details;
        };

        $details = $mapData($data);

        ClickPaymentLog::create($details);
    }


    /**
     * The function `data` generates an array of data for processing a sale transaction with ClickPay,
     * including order details and customer information.
     *
     * @param order The `data` function you provided seems to be a method that prepares data for a
     * payment transaction. It takes two parameters, `` and ``.
     * @param amount The `amount` parameter in the `data` function represents the total amount of the
     * order that is being processed. It is used to specify the total cost of the items in the cart
     * that the customer is purchasing. This amount will be used in the payment processing flow to
     * charge the customer accordingly.
     *
     * @return An array is being returned with the following key-value pairs:
     * - 'profile_id' => value from ->settings['profileId']
     * - 'tran_type' => 'sale'
     * - 'tran_class' => 'ecom'
     * - 'cart_id' => "Order ID " concatenated with the ->id
     * - 'cart_description' => "Dummy Order " concatenated with the ->
     */
    public function data($order, $amount)
    {
        return  [
            'profile_id' => $this->settings['profileId'],
            'tran_type' => 'sale',
            'tran_class' => "ecom",
            'cart_id' =>  "Order ID " . $order->id,
            'cart_description' => "Dummy Order " . $order->id,
            'cart_currency' => $this->settings['currency'],
            'cart_amount' => $amount,
            "paypage_lang" => "en",
            "hide_shipping"=> true,
            "customer_details" => [
                "name" => $order->customerName,
                "phone" => $order->customerPhone,
                "street1" => $order->customerAddress,
                "city"=> $order->city->name->value,
                "country" => 'SA',
                "state" => '01',
            ],

            'callback' => url('orders/confirm/clickpay-yourcallback'),
            'return' => url('orders/confirm/clickpay'),
            // Add more fields as required by ClickPay
        ];
    }
}
