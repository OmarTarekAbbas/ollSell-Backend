<?php

namespace Modules\Order\Http\Controllers;

use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Entities\Country;
use Modules\CoreData\Service\CityService;
use Modules\CoreData\Service\CountryService;
use Modules\MasterCatalog\Entities\Product;
use Modules\Order\Actions\Order\CheckDuplicatedOrderAction;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Service\OrderService;
use Revolution\Google\Sheets\Facades\Sheets;

class SheetController extends BasicController
{
    protected $countryService;

    protected $cityService;

    protected $service;

    /**
     * Constructor to initialize services.
     */
    public function __construct(CountryService $countryService, CityService $cityService, OrderService $service)
    {
        $this->countryService = $countryService;
        $this->cityService = $cityService;
        $this->service = $service;
    }

    public function test()
    {
        // Fetch values from the Google Sheet
        $spreadsheetId = env('POST_SPREADSHEET_ID');
        $values = Sheets::spreadsheet($spreadsheetId)->sheet('Sheet1')->all();
        // Retrieve the header row
        $header = array_shift($values);

        // Check if "Validation Error" column exists, if not, add it
        if (! in_array('Validation Error', $header)) {
            $header[] = 'Validation Error';
            Sheets::spreadsheet($spreadsheetId)->sheet('Sheet1')->range('A1')->update([$header]);
        }

        $count = 0;

        foreach ($values as $index => $row) {
            if ($index < 1151) continue;

            // Ensure all columns are set to null if empty
            $row = array_map(function ($value) {
                return $value === '' ? null : $value;
            }, $row);

            $phoneNumber = $this->sanitizeSaudiPhoneNumber($row[1]);
            $message = [];

            // Map Google Sheet columns to your database fields
            $mappedRow = [
                'customerName' => $row[0] ?? null,
                'customerPhone' => $phoneNumber,
                'customerCity' => $row[2] ?? null,
                'customerAddress' => $row[3] ?? null,
                'quantity' => $row[4] ?? null,
                'itemCost' => $row[5] ?? null,
                'orderCost' => $row[6] ?? null,
                'shippingCost' => $row[7] ?? null,
                'productName' => $row[8] ?? null,
                'productVariant' => $row[9] ?? null,
                'externalId' => $row[10] ?? null,
                'tagerId' => $row[11] ?? null,
                'orderDate' => $row[12] ?? null,
                'productSku' => $row[13] ?? null,
                'extraDataOne' => $row[14] ?? null,
                'extraDataTwo' => $row[15] ?? null,
                'utmSource' => $row[16] ?? null,
                'utmCampaign' => $row[17] ?? null,
                'validationError' => $row[18] ?? null,
                'olldropId' => $row[19] ?? null,
            ];

            if ($mappedRow['validationError'] || $mappedRow['olldropId']) continue;

            // Perform validations
            if (empty($mappedRow['customerName']) || empty($mappedRow['customerPhone']) || empty($mappedRow['customerAddress'])) {
                $message[] = 'Found Empty column';
            }

            if (strlen($mappedRow['customerPhone']) != 10) {
                $message[] = 'The phone field must be 10 numbers.';
            }

            // Additional validations and order creation logic
            $request = request();
            $request->merge([
                'is_duplicated' => false,
                'duplicated_order_ids' => null,
                'fresh_duplicated' => false,
            ]);

            $itemArray = [];
            $items = explode(',', $mappedRow['productSku']);
            $totalPriceForProduct = [];
            $totalSellingPriceForProduct = [];

            foreach ($items as $item) {
                $messageProduct = '';
                $product = Product::where('sku', $item)->first();
                if (! $product) {
                    $messageProduct = 'Product SKU not found: '.$item;

                    continue;
                }

                $productQuantity = $product->quantity;
                if ($productQuantity <= 0) {
                    $messageProduct = 'Product Is out of stock';

                    continue;
                }

                if ($productQuantity < $mappedRow['quantity']) {
                    $messageProduct = 'Product inventory is not sufficient for '.$mappedRow['quantity'];

                    continue;
                }

                if ($mappedRow['itemCost'] <= $product['cost_price']) {
                    $messageProduct = 'The Selling Price must be greater than the Cost Price.';

                    continue;
                }

                $totalPriceForProduct[] = ($mappedRow['itemCost'] * $mappedRow['quantity']);
                $totalSellingPriceForProduct[] = ($product['cost_price'] * $mappedRow['quantity']);
                $itemArray[] = [
                    'product' => $product['id'],
                    'quantity' => $mappedRow['quantity'],
                    'unitPrice' => $mappedRow['itemCost'],
                    'variant' => [0 => 'null'],
                ];
            }

            if (! count($itemArray)) {
                $append = [
                    [$messageProduct],
                ];

                $rowNumber = $index + 2; // Adding 2 because index is zero-based and header is in the first row

                Sheets::spreadsheet($spreadsheetId)
                    ->sheet('Sheet1')
                    ->range("S{$rowNumber}")
                    ->update($append);

                sleep(2);

                continue;
            }

            $totalQuantity = collect($itemArray)->sum('quantity');
            $countOrderItem = collect($items)->count();
            $totalPrice = collect($totalPriceForProduct)->sum();
            $costPrice = collect($totalSellingPriceForProduct)->sum();
            $shippingFees = setting('shipping_fee') ?? 25;

            $request->merge(['code' => 'sa']);
            $code = 'sa';

            $country = Country::where('code', $code)->first();
            $countryId = $country['id'];

            $request->merge(['name' => $mappedRow['customerCity']]);

            $city = $this->cityService->findBy($request, get: 'first');
            if (! $city || $city->country_id !== $countryId) {
                $message[] = 'City not correct or not related to the country';
            }

            if ($this->checkForErrors($message, $spreadsheetId, $index)) continue;

            $cityId = $city->id;

            $paymentMethod = ($mappedRow['shippingCost'] === 'Wallet') ? 3 : 2;

            $totalProductVat = $this->getOrderItemVatImport($product, $row);
            $netProfit = $this->netProfitImport($product, $row);
            $request->merge([
                'shippingFees' => $shippingFees,
                'shippingMethod' => 'shippingMethod',
                'totalQuantity' => $totalQuantity,
                'subTotal' => $totalPrice,
                'grandTotal' => ($totalPrice + $shippingFees),
                'dropshipper_id' => 69, // operation team account
                'status_id' => ($mappedRow['shippingCost'] == 'Wallet') ? OrderEnum::PAY_PENDING_STATUS : OrderEnum::NEW_STATUS,
                'customerName' => $mappedRow['customerName'],
                'customerPhone' => $mappedRow['customerPhone'],
                'customerAddress' => $mappedRow['customerAddress'],
                'customerLocation' => $mappedRow['utmSource'],
                'district' => $mappedRow['customerAddress'],
                'country_id' => $countryId,
                'customerCity' => $cityId,
                'countOrderItem' => $countOrderItem,
                'paymentMethod' => $paymentMethod,
                'items' => $itemArray,
                'costPrice' => $costPrice,
                'phone_code' => 966,
                'net_profit' => $netProfit,
                'totalVat' => $totalProductVat,
                'is_import' => 1,
                'weight' => 0,
                'source_platform' => 'tiktokSheet',

            ]);

            $duplicatedOrders = (new CheckDuplicatedOrderAction($request))->execute();
            $request->merge([...$duplicatedOrders]);

            if ($request->fresh_duplicated) {
                $message[] = 'Order is duplicated.';
            }

            if ($this->checkForErrors($message, $spreadsheetId, $index)) continue;

            $data = $this->service->storeImport($request);

            // Append order id to sheet
            $append = [
                [$data->id],
            ];

            $rowNumber = $index + 2;

            Sheets::spreadsheet($spreadsheetId)
                ->sheet('Sheet1')
                ->range("T{$rowNumber}")
                ->update($append);

            sleep(2);

            if ($data->is_duplicated) {
                $duplicatedOrderIds = json_decode($data->duplicated_order_ids, true);
                foreach ($duplicatedOrderIds as $duplicatedOrderId) {
                    $duplicatedOrder = Order::find($duplicatedOrderId);
                    if ($duplicatedOrder) {
                        $duplicatedOrder->update([
                            'status_id' => OrderEnum::PAY_PENDING_STATUS,
                            'is_import' => 1,
                        ]);
                    }
                }
                $data->status_id = OrderEnum::PAY_PENDING_STATUS;
                $data->is_import = 1;
                $data->save();
            }

            $this->clearError($spreadsheetId, $index);

            $count++;
        }
    }

    private function checkForErrors($message, $spreadsheetId, $index)
    {
        if (count($message)) {
            $append = [
                [$message[0]],
            ];

            $rowNumber = $index + 2;

            Sheets::spreadsheet($spreadsheetId)
                ->sheet('Sheet1')
                ->range("S{$rowNumber}")
                ->update($append);

            sleep(2);

            return true;
        }

        return false;
    }

    private function clearError($spreadsheetId, $index)
    {
        $append = [
            null,
        ];

        $rowNumber = $index + 2;

        Sheets::spreadsheet($spreadsheetId)
            ->sheet('Sheet1')
            ->range("S{$rowNumber}")
            ->update($append);

        sleep(2);
    }

    public function getOrderItemVatImport($product, $row)
    {
        $itemSellingPrice = $row[6];
        $productBasePrice = $product->is_discount ? $product->priceAfterDiscount : $product->cost_price;
        $baseVat = $productBasePrice * setting('shipping_fee');
        $totalProfit = $itemSellingPrice - $productBasePrice;
        $profitVat = $totalProfit * setting('shipping_fee');
        $netProfit = $totalProfit - $profitVat;
        $totalVat = $baseVat + $profitVat;

        return $totalVat * $row[4];
    }

    public function netProfitImport($product, $row)
    {
        $netProfit = $this->totalProfitImport($product, $row) - $this->vatProfitImport($product, $row);

        return $netProfit;
    }

    public function totalProfitImport($product, $row)
    {
        return ($row[6] - $product->cost_price) * $row[4];
    }

    public function vatProfitImport($product, $row)
    {
        $totalProfit = $row[6] - $product->cost_price;

        return ($totalProfit * setting('shipping_fee')) * $row[4];
    }

    public function sanitizeSaudiPhoneNumber($phoneNumber)
    {
        $sanitizedNumber = preg_replace('/^(00966|966)/', '', $phoneNumber);
        if (! preg_match('/^0/', $sanitizedNumber)) {
            $sanitizedNumber = '0'.$sanitizedNumber;
        }

        return $sanitizedNumber;
    }
}
