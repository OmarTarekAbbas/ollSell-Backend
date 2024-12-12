<?php

namespace Modules\StoreIntegrations\Actions\Order;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderEnum;
use Modules\Acl\Entities\Dropshipper;
use Modules\Basic\Actions\BaseAction;
use Modules\Order\Enums\PlatformEnum;
use Modules\Order\Service\OrderService;
use Modules\CoreData\Service\CityService;
use Modules\MasterCatalog\Entities\Product;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\Store\Entities\DropshipperEcommerce;
use Modules\Store\Entities\DropshipperMappingOrder;
use Modules\Order\Actions\Order\CreateShipmentOrderAction;
use Modules\Order\Actions\Order\CheckDuplicatedOrderAction;

class Created extends BaseAction
{
    use validationRulesTrait;

    public Order $order;

    public function handle()
    {
        $request = request();
        $service = app(OrderService::class);

        $city = $this->getCity();
        $dropshipper = $this->getDropshipper();

        if (!$dropshipper || !$city || !$this->hasValidItems($items, $message)) {
            return $this->handleOrderError($this->prepareErrorData($message));
        }

        $newData = $this->prepareOrderData($city, $dropshipper, $items);
        $duplicatedOrders = $this->checkForDuplicatedOrders($request, $newData);

        if (!empty($duplicatedOrders['fresh_duplicated'])) {
            return $this->handleOrderError($this->prepareErrorData(['Order is duplicated.']));
        }

        $this->order = $service->store($request->merge($newData));

        $this->handlePaymentAndMapping($dropshipper, $duplicatedOrders);
    }

    private function getCity()
    {
        $cityAlias = $this->request->data['shipments'][0]['ship_to']['city'] ?? null;
        return $cityAlias ? app(CityService::class)->search(new Request(['alias' => $cityAlias])) : null;
    }

    private function getDropshipper()
    {
        return DropshipperEcommerce::where('store_id', $this->request->merchant)
            ->where('store_type', 'salla')
            ->first();
    }

    private function hasValidItems(&$items, &$message): bool
    {
        $items = $this->request->data['items'] ?? [];
        if (empty($items) || !isset($items[0]['sku'])) {
            $message[] = 'No products found';
            return false;
        }

        foreach ($items as $item) {
            $product = Product::where('sku', $item['sku'])->first();
            if (!$product) {
                $message[] = "Product SKU not found: {$item['sku']}";
                return false;
            }
            if (($item['amounts']['total']['amount'] / $item['quantity']) <= $product->cost_price) {
                $message[] = 'The Selling Price must be greater than the Cost Price.';
                return false;
            }
        }

        return true;
    }

    private function prepareOrderData($city, $dropshipper, $items): array
    {
        $countOrderItems = collect($items)->count();
        
        return [
            'customerCity' => $city->id,
            'customerAddress' => $this->request->data['shipments'][0]['ship_to']['address_line'],
            'customerLocation' => $this->request->data['shipments'][0]['ship_to']['latitude'] . ',' . $this->request->data['shipments'][0]['ship_to']['longitude'],
            'paymentMethod' => $this->determinePaymentMethod($dropshipper),
            'customerCountry' => '178',
            'phone_code' => '996',
            'dropshipper_id' => $dropshipper->dropshipper_id ?? null,
            'source_platform' => PlatformEnum::SALLA_PLATFORM,
            'items' => $this->formatItems($items),
            'is_duplicated' => false,
            'duplicated_order_ids' => null,
            'fresh_duplicated' => false,
            'customerName' => $this->request->data['shipments'][0]['ship_to']['name'] ?? null,
            'customerPhone' => $this->handlePhoneKSA($this->request->data['shipments'][0]['ship_to']['phone']),
            'countOrderItem' => $countOrderItems,
        ];
    }

    private function formatItems(array $items): array
    {
        $formattedItems = [];
        foreach ($items as $item) {
            $product = Product::where('sku', $item['sku'])->first();
            $formattedItems[] = [
                'product' => $product->id,
                'quantity' => $item['quantity'],
                'sellingPrice' => $item['amounts']['total']['amount'] / $item['quantity'],
                'variant' => ['null']
            ];
        }
        return $formattedItems;
    }

    private function checkForDuplicatedOrders($request, &$newData)
    {
        $duplicatedOrders = (new CheckDuplicatedOrderAction(request: $request))->execute();
        $newData = array_merge($newData, $duplicatedOrders);
        return $duplicatedOrders;
    }

    private function createShipmentOrder()
    {
        app(CreateShipmentOrderAction::class)->execute(data: $this->order);
    }

    // TODO :: check logic here
    private function handlePaymentAndMapping($dropshipper, $duplicatedOrders)
    {
        if ($this->order && $this->order->paymentMethod == 3) {

            $checkOrder = DropshipperMappingOrder::where('model_type', 'salla')->where('model_id', $this->request->data['id'])->first();

            $this->order->update(['status_id' => OrderEnum::PAY_PENDING_STATUS]);
            
            if ($checkOrder) {
                return true;
            }
        }

        DropshipperMappingOrder::create([
            'dropshipper_id' => $dropshipper->id,
            'model_type' => 'salla',
            'model_id' => $this->request->data['id'],
            'order_id' => $this->order->id
        ]);
    }

    private function prepareErrorData(array $message): array
    {
        return [
            'order_id' => $this->request->data['id'],
            'message' => implode(', ', $message),
            'customerCity' => $this->request->data['shipments'][0]['ship_to']['city'] ?? null,
            'paymentMethod' => $this->request->data['payment_method'] ?? 'cod',
        ];
    }

    public function handleOrderError($data)
    {
        $errorData = array_merge([
            'name' => $this->request->data['shipments'][0]['ship_to']['name'] ?? null,
            'phone' => $this->request->data['shipments'][0]['ship_to']['phone'] ?? null,
            'address' => $this->request->data['shipments'][0]['ship_to']['address_line'] ?? null,
            'district' => 'KSA',
            'location' => null,
            'country' => 'KSA',
        ], $data);

        $this->writeErrorToCSV($errorData);
    }

    private function writeErrorToCSV(array $errorData)
    {
        $directoryPath = public_path("missings/salla_order/" . today()->format('Y-m-d'));
        if (!is_dir($directoryPath)) mkdir($directoryPath, 0777, true);

        $csvFile = fopen($directoryPath . "/orders_failed_rows.csv", "a");
        fputcsv($csvFile, $errorData);
        fclose($csvFile);
    }

    private function determinePaymentMethod($dropshipper)
    {
        $dropshipper = Dropshipper::find($dropshipper->dropshipper_id);

        $paymentMethod = $this->request->data['payment_method'];
        if ($paymentMethod == 'cod') {
            return 2;
        } elseif (in_array($paymentMethod, ['tap', 'tabby', 'bank']) && $dropshipper->DropshipperOptionCheck('convert_payment_online_to_wallet')) {
            return 3;
        }
        return 2;
    }
}
