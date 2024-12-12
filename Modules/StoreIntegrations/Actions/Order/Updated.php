<?php

namespace Modules\StoreIntegrations\Actions\Order;


use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderEnum;
use Illuminate\Support\Facades\Log;
use Modules\Basic\Actions\BaseAction;
use Modules\Order\Enums\PlatformEnum;
use Modules\Order\Service\OrderService;
use Modules\CoreData\Service\CityService;
use Modules\MasterCatalog\Entities\Product;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\Store\Entities\DropshipperEcommerce;
use Modules\Store\Entities\DropshipperMappingOrder;
use Modules\Order\Actions\Order\CreateShipmentOrderAction;
use Modules\Finance\Actions\Transaction\PayByWalleteAutomaticAction;

class Updated extends BaseAction
{
    use validationRulesTrait;

    public Order $order;
    public array $errorMessages = [];

    public function handle()
    {
        Log::channel('salla')->info('update:');
        $this->order = null;
        $request = request();
        $service = app()->make(OrderService::class);

        $orderId = $this->request->data['id'];
        $existingOrderMapping = $this->getExistingOrderMapping($orderId);

        if ($existingOrderMapping) {
            return $this->handleExistingOrder($existingOrderMapping);
        }

        try {
            $dropshipper = $this->getDropshipper();
            $city = $this->getCity();
            $items = $this->getOrderItems();

            if (!$dropshipper || !$city || empty($items)) {
                $this->handleOrderError([
                    'order_id' => $orderId,
                    'message' => implode(',', $this->errorMessages)
                ]);
                return;
            }

            $this->order = $this->createOrder($dropshipper, $city, $items);

            if ($this->order && $this->isEligibleForShipment()) {
                App(CreateShipmentOrderAction::class)->execute(data: $this->order);
            }

            $this->mapOrder($dropshipper, $orderId, $this->order->id);
        } catch (\Exception $exception) {
            Log::error("Order update failed: " . $exception->getMessage());
            $this->handleOrderError([
                'order_id' => $orderId,
                'message' => 'An error occurred during order processing.'
            ]);
        }
    }

    private function getExistingOrderMapping($orderId)
    {
        return DropshipperMappingOrder::where('model_type', 'salla')
            ->where('model_id', $orderId)
            ->first();
    }

    private function getDropshipper()
    {
        $dropshipper = DropshipperEcommerce::where('store_id', $this->request->merchant)
            ->where('store_type', 'salla')
            ->first();

        if (!$dropshipper) {
            $this->addError('Dropshipper not found.');
        }
        return $dropshipper;
    }

    private function getCity()
    {
        $cityName = $this->request->data['shipments'][0]['ship_to']['city'] ?? null;
        $city = app()->make(CityService::class)->search(new Request(['alias' => $cityName]));

        if (!$city) {
            $this->addError("City not found.");
        }
        return $city;
    }

    private function getOrderItems()
    {
        $items = $this->request->data['items'] ?? [];
        $itemArray = [];
        $totalPriceForProduct = [];
        $totalSellingPriceForProduct = [];
        $totalProductVatArray = [];
        $netProfitArray = [];

        foreach ($items as $item) {
            $product = Product::where('sku', $item['sku'])->first();
            if (!$product) {
                $this->addError("Product SKU not found: {$item['sku']}");
                continue;
            }

            $isValidPrice = ($item['amounts']['total']['amount'] / $item['quantity']) > $product->cost_price;
            if (!$isValidPrice) {
                $this->addError("Selling Price must be greater than the Cost Price.");
            }

            $totalPriceForProduct[] = $item['amounts']['total']['amount'];
            $totalSellingPriceForProduct[] = $product->cost_price * $item['quantity'];
            $totalProductVatArray[] = $this->getOrderItemVatImport($product, $item);
            $netProfitArray[] = $this->netProfitImport($product, $item);

            $itemArray[] = [
                'product' => $product->id,
                'quantity' => $item['quantity'],
                'sellingPrice' => ($item['amounts']['total']['amount'] / $item['quantity']),
                'variant' => [0 => 'null'],
            ];
        }

        return empty($itemArray) ? null : compact('itemArray', 'totalPriceForProduct', 'totalSellingPriceForProduct', 'totalProductVatArray', 'netProfitArray');
    }

    private function getOrderItemVatImport($product, $item)
    {
        $vatRate = setting('vat_rate') ?? 0.15; // Assuming a 15% VAT rate if not defined
        $itemPrice = $item['amounts']['total']['amount'];
        return $itemPrice * $vatRate;
    }

    private function netProfitImport($product, $item)
    {
        $sellingPrice = $item['amounts']['total']['amount'];
        $costPrice = $product->cost_price * $item['quantity'];
        return $sellingPrice - $costPrice;
    }

    private function createOrder($dropshipper, $city, $items)
    {
        $shippingFees = setting('shipping_fee') ?? 25;
        $totalPrice = collect($items['totalPriceForProduct'])->sum();
        $costPrice = collect($items['totalSellingPriceForProduct'])->sum();
        $totalProductVat = collect($items['totalProductVatArray'])->sum();
        $netProfit = collect($items['netProfitArray'])->sum();

        $mergedRequestData = [
            'is_duplicated' => false,
            'shippingFees' => $shippingFees,
            'totalQuantity' => collect($items['itemArray'])->sum('quantity'),
            'subTotal' => $totalPrice,
            'grandTotal' => $totalPrice + $shippingFees,
            'costPrice' => $costPrice,
            'net_profit' => $netProfit,
            'totalVat' => $totalProductVat,
            'customerCity' => $city->id,
            'paymentMethod' => $this->getPaymentMethod(),
            'customerCountry' => '178', // Saudi Arabia
            'phone_code' => '996',
            'dropshipper_id' => $dropshipper->dropshipper_id,
            'source_platform' => PlatformEnum::SALLA_PLATFORM,
            'items' => $items['itemArray'],
        ];

        $request = request()->merge($mergedRequestData);

        return app()->make(OrderService::class)->store($request);
    }

    private function mapOrder($dropshipper, $orderId, $newOrderId)
    {
        DropshipperMappingOrder::create([
            'dropshipper_id' => $dropshipper->id,
            'model_type' => 'salla',
            'model_id' => $orderId,
            'order_id' => $newOrderId
        ]);
    }

    private function handleExistingOrder($orderMapping)
    {
        $order = Order::find($orderMapping->order_id);
        if ($order && $order->status_id === OrderEnum::PAY_PENDING_STATUS) {
            $this->processWalletPayment($order);
        }

        if ($this->isEligibleForShipment($order)) {
            App(CreateShipmentOrderAction::class)->execute(data: $order);
        }
    }

    private function processWalletPayment($order)
    {
        $checkOrder = DropshipperMappingOrder::where('model_type', 'salla')
            ->where('model_id', $this->request->data['id'])
            ->first();

        if ($checkOrder && $this->isPaymentComplete()) {
            $check = app(PayByWalleteAutomaticAction::class)->execute($checkOrder);
            if ($check) {
                app(OrderService::class)->payWallet($checkOrder);
            }
        }
    }

    private function handleOrderError(array $data)
    {
        $directoryPath = public_path("missings/salla_order/" . today()->format('Y-m-d'));
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        $csvFile = fopen("{$directoryPath}/orders_failed_rows.csv", "a");
        fputcsv($csvFile, [
            'order_id' => $data['order_id'] ?? null,
            'message' => $data['message'] ?? null,
            'name' => $data['customerName'] ?? null,
            'phone' => $data['customerPhone'] ?? null,
            'address' => $data['customerAddress'] ?? null,
            'district' => $data['customerCity'] ?? null,
            'city' => $data['customerCity'] ?? null,
            'district_code' => $data['customerDistrict'] ?? null,
            'total_price' => $data['total_price'] ?? null,
        ]);
        fclose($csvFile);
    }

    private function addError($message)
    {
        $this->errorMessages[] = $message;
    }

    private function getPaymentMethod()
    {
        return $this->request->data['payment_method'] == 'paid' ? 'Paid' : 'COD';
    }

    private function isEligibleForShipment()
    {
        return $this->request->data['payment_method'] == 'paid';
    }

    private function isPaymentComplete()
    {
        return $this->request->data['payment_method'] == 'paid';
    }
}
