<?php

namespace Modules\Order\Actions\PendingOrder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\DB;
use Modules\Order\Enums\OrderEnum;
use Illuminate\Support\Facades\Log;
use Modules\CoreData\Entities\Country;
use Modules\Order\Enums\PlatformEnum;
use Modules\Order\Service\OrderService;
use Modules\Order\Entities\PendingOrder;
use Modules\CoreData\Service\CityService;
use Modules\MasterCatalog\Entities\Product;
use Modules\Order\Entities\PendingOrderItem;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\Order\Actions\Order\CheckSubmitDuplicatedOrderAction;
use Modules\Order\Actions\Order\CreateShipmentOrderAction;
use Modules\Order\Enums\PaymentEnum;
use Modules\Order\Service\PendingOrderImportService;

class ScanPendingOrderAction
{
    use validationRulesTrait;

    protected $pendingOrders;

    public function __construct($pendingOrders)
    {
        $this->pendingOrders = $pendingOrders;
    }

    public function execute()
    {
        foreach ($this->pendingOrders as $row) {
            $pendingOrder = PendingOrder::find($row->id);
            $request = request();
            $validationPending = App(ValidationPendingOrdersAction::class);
            $items = $this->mapPrepareProduct($pendingOrder->pendingOrderItems);
            $city = app(CityService::class)->search(new Request(['alias' => array_merge(
                [$pendingOrder->customer_city],
                array_filter(explode(' ', $pendingOrder->customer_city))
            )]));
            $city_in_country = true;
            $country = $this->mapPrepareCountry($pendingOrder->customer_country);
            if ($country) {
                if ($city && $city->country_id != $country->id) {
                    $city_in_country = false;
                }
            }
            $paymentMethod = $pendingOrder->payment_method;
            if ($paymentMethod == 1 && user()->DropshipperOptionCheck('convert_payment_online_to_wallet')) {
                $paymentMethod = 3;
            }
            $requestOrder = [
                'customer_name' => $pendingOrder->customer_name,
                'customer_phone' => $pendingOrder->customer_phone,
                'customer_address' => $pendingOrder->customer_address,
                'district' => $pendingOrder->district,
                'source_platform' => $pendingOrder->source_platform,
                'country_id' => $country->id ?? null,
                'city_id' => $city->id ?? null,
                'city_in_country' => $city_in_country,
                'customer_city' => $pendingOrder->customer_city,
                'customer_country' => $pendingOrder->customer_country,
                'payment_method' => $paymentMethod,
                'items' => $items,
                'is_duplicated' => false,
                'invalid' => false,
                'duplicated_order_ids' => null,
                'message' => null,
            ];
            $requestOrder = $validationPending->allValidation($requestOrder);
            app(PendingOrderImportService::class)->update($request->merge($requestOrder), $row->id);
        }
        return true;
    }

    public function mapPrepareProduct($items)
    {
        $itemArray = [];
        foreach ($items as $value) {
            $product = Product::where('sku', $value['sku'])->first();
            if ($product) {
                $can = true;
                $ids = $product->product_dropshippers->pluck('dropshipper_id');
                if ($ids->count()) {
                    if (!in_array(user()->id, $ids->toArray())) {
                        $can = false;
                    }
                }
                if ($product->isApproved && $product->status && $can) {
                    $itemArray[] = [
                        'sku' => $value['sku'] ?? null,
                        'product' => ($product) ? $product->id : null,
                        'productData' => ($product) ? $product : null,
                        'quantity' => $value['quantity'] ?? null,
                        'selling_price' => $value['selling_price'] ?? null,
                    ];
                } else {
                    $product = null;
                    $itemArray[] = [
                        'sku' => $value['sku'] ?? null,
                        'product' => ($product) ? $product->id : null,
                        'productData' => ($product) ? $product : null,
                        'quantity' => $value['quantity'] ?? null,
                        'selling_price' => $value['selling_price'] ?? null,
                    ];
                }
            } else {
                $product = null;
                $itemArray[] = [
                    'sku' => $value['sku'] ?? null,
                    'product' => ($product) ? $product->id : null,
                    'productData' => ($product) ? $product : null,
                    'quantity' => $value['quantity'] ?? null,
                    'selling_price' => $value['selling_price'] ?? null,
                ];
            }
        }
        return $itemArray;
    }

    public function mapPrepareCountry($row)
    {
        $code = null;
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
