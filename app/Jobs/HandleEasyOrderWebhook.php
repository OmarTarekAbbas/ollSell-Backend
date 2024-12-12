<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Modules\Acl\Entities\Dropshipper;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\CoreData\Entities\Country;
use Modules\CoreData\Service\CityService;
use Modules\Finance\Actions\Transaction\PayByWalleteAutomaticAction;
use Modules\MasterCatalog\Entities\Product;
use Modules\Order\Actions\PendingOrder\ValidationPendingOrdersAction;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\PlatformEnum;
use Modules\Order\Service\OrderService;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Spatie\WebhookClient\Models\WebhookCall;

/**
 * @property WebhookCall $webhookCall
 * @property Collection $payload
 * @property Order $order
 */
class HandleEasyOrderWebhook extends ProcessWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, validationRulesTrait;

    public WebhookCall $webhookCall;
    public Collection $payload;
    public Order $order;

    /**
     * Create a new job instance.
     *
     * @param WebhookCall $webhookCall
     */
    public function __construct(WebhookCall $webhookCall)
    {
        parent::__construct($webhookCall);
    }

    public function handle(Request $request)
    {
        try {
            $city = $city1 = null;
            $payment = 'cod';
            $city_in_country = false;
            $service = app()->make(OrderService::class);
            $this->payload = collect($this->webhookCall->payload);
            $data = json_decode($this->payload, true);
            $newData['message'] = null;
            $replacements = [
                'cart_items' => 'items',
                'payment_method' => 'paymentMethod',
                'full_name' => 'customerName',
                'phone' => 'customerPhone',
                'address' => 'customerAddress',
                'government' => 'customerCity',
                'id' => 'easy_order_id',
            ];
            foreach ($replacements as $oldKey => $newKey) {
                if (isset($data[$oldKey])) {
                    $newData[$newKey] = $data[$oldKey];
                    unset($data[$oldKey]);
                }
            }
            $dropshipper = Dropshipper::find(69);
            $paymentMethod = $newData['paymentMethod'] ?? null;
            if ($paymentMethod == 'cod') {
                $paymentMethod = 2;
            } elseif ($paymentMethod == 'tap' && $dropshipper->DropshipperOptionCheck('convert_payment_online_to_wallet')) {
                $payment = 'tap';
                $paymentMethod = 3;
            } elseif ($paymentMethod == 'tabby' && $dropshipper->DropshipperOptionCheck('convert_payment_online_to_wallet')) {
                $payment = 'tabby';
                $paymentMethod = 3;
            }
            if ($paymentMethod == 3 && isset($data['status']) && $data['status'] == 'paid') {
                $checkOrder = Order::where('easy_order_id', $newData['easy_order_id'])->first();
                if ($checkOrder && $dropshipper->DropshipperOptionCheck('automatic_pay_from_profit_at_wallet')) {
                    $check = app(PayByWalleteAutomaticAction::class)->execute($checkOrder);
                    if ($check) {
                        $service->payWallet($checkOrder);
                    }
                    return true;
                }
            }
            // Validate city
            if (isset($newData['customerCity'])) {
                $city = app()->make(CityService::class)
                    ->search(new Request(['alias' => array_merge([$newData['customerCity']], array_filter(explode(
                        ' ',
                        $newData['customerCity']
                    )))]));
                if (!$city) {
                    $city1 = app()->make(CityService::class)
                        ->search(new Request(['alias' => array_merge(
                            [$newData['customerAddress']],
                            array_filter(explode(
                                ' ',
                                $newData['customerAddress']
                            ))
                        )]));
                }
            }
            $country = $this->mapPrepareCountry('KSA');
            if ($country) {
                if ($city) {
                    if ($city && $city->country_id != $country->id) {
                        $city_in_country = false;
                    } else {
                        $city_in_country = true;
                    }
                } elseif ($city1) {
                    if ($city1 && $city1->country_id != $country->id) {
                        $city_in_country = false;
                    } else {
                        $city_in_country = true;
                    }
                }
            }
            // Process items
            $items = $this->processItems($newData['items'] ?? [], $payment);
            $requestOrder = [
                'dropshipper_id' => 69,
                'customer_name' => str_replace('.', ' ', $newData['customerName']) ?? null,
                'customer_phone' => $newData['customerPhone'] ?? null,
                'customer_address' => $newData['customerAddress'] ?? null,
                'district' => $newData['customerAddress'] ?? null,
                'country_id' => 178,
                'customerCountry' => 178,
                'city_id' => $city ? $city->id : ($city1 ? $city1->id : null),
                'city_in_country' => $city_in_country,
                'customer_city' => $city ? $newData['customerCity'] : ($city1 ? $newData['customerAddress'] : null),
                'customerCity' => $city ? $city->id : ($city1 ? $city1->id : null),
                'old_customer_city' => $newData['customerCity'] ?? null,
                'customer_country' => 'KSA',
                'payment_method' => $paymentMethod,
                'paymentMethod' => $paymentMethod,
                'old_paymentMethod' => $newData['paymentMethod'] ?? null,
                'items' => $items,
                'is_duplicated' => false,
                'invalid' => false,
                'duplicated_order_ids' => null,
                'phone_code' => '996',
                'source_platform' => PlatformEnum::EASYORDER_PLATFORM,
                'created_platform' => PlatformEnum::EASYORDER_PLATFORM,
                'easy_order_id' => $newData['easy_order_id'],
            ];
            $request->merge(array_merge($newData, $requestOrder));
            $requestOrder = App(ValidationPendingOrdersAction::class)->allValidation($requestOrder, false);
            $requestOrder['customerPhone'] = $requestOrder['customer_phone'];
            $requestOrder['customerName'] = $requestOrder['customer_name'];
            if ($requestOrder['invalid']) {
                $this->handleOrderError($requestOrder);
            } else {
                $this->order = $service->store($request, onlinePayment: in_array($payment, ['tabby', 'tap']));
            }
        } catch (\Exception $e) {
            $this->handleOrderError($requestOrder ?? $newData);
            Log::channel('easy-order-api')
                ->info("id : " . ($data['id'] ?? ($newData['easy_order_id'] ?? null)) . " Error : " . $e->getMessage());
        }
    }

    private function processItems($items, $payment = 'cod')
    {
        $itemArray = [];
        $totalQuantity = 0;
        $shippingCost = 25; // Assuming shipping cost is fixed at 25
        foreach ($items as $item) {
            $sku = $item['product']['sku'] ?? null;
            $quantity = $item['quantity'] ?? 0;
            $totalQuantity += $quantity;
            $product = Product::where('sku', $sku)->first();
            $itemArray[] = [
                'sku' => $sku,
                'price' => $item['price'] ?? 0,
                'product' => ($product) ? $product->id : null,
                'productData' => ($product) ? $product : null,
                'quantity' => $quantity,
                'variant' => [null],
            ];
        }
        $fees = $totalQuantity ? ($shippingCost / $totalQuantity) : 25;
        foreach ($itemArray as $key => $item) {
            $itemArray[$key]['selling_price'] = $itemArray[$key]['price'] - $fees;
            $itemArray[$key]['sellingPrice'] = $itemArray[$key]['price'] - $fees ;
        }
        return $itemArray;
    }

    public function handleOrderError($data)
    {
        $newData = [
            'name' => $data['customerName'] ?? null,
            "phone" => $data['customer_phone'] ?? null,
            'address' => $data['customer_address'] ?? null,
            'district' => $data['customer_address'] ?? null,
            'city' => $data['old_customer_city'] ?? null,
            'country' => 'KSA',
            'source_platform' => PlatformEnum::EASYORDER_PLATFORM,
            'paymentMethod' => $data['old_paymentMethod'] ?? null,
        ];
        // Add item details
        foreach ($data['items'] ?? [] as $index => $item) {
            $newData["sku"][] = $item['sku'] ?? null;
            $newData["quantity"][] = $item['quantity'] ?? null;
            $newData["selling_price"][] = $item['selling_price'] ?? null;
        }
        $newData["sku"] = implode(',', $newData["sku"] ?? []);
        $newData["quantity"] = implode(',', $newData["quantity"] ?? []);
        $newData["selling_price"] = implode(',', $newData["selling_price"] ?? []);
        $message = json_decode($data['message']);
        $newData['message'] = $message ? implode(',', $message) : null;
        $newData['easy_order_id'] = $data['easy_order_id'] ?? ($data['id'] ?? null);
        $csvFile = fopen(
            public_path("missings/easy_order/" . today()->format('Y-m-d') . "/orders_failed_rows.csv"),
            "a"
        );
        fputcsv($csvFile, $newData);
        fclose($csvFile);
    }

    public function mapPrepareCountry($row)
    {
        $code = '';
        if ($row == 'KSA') {
            $code = 'sa';
        }
        if ($row == 'UAE') {
            $code = 'ae';
        }
        if ($row == 'EGY') {
            $code = 'eg';
        }
        return Country::where('code', $code)->first();
    }
}
