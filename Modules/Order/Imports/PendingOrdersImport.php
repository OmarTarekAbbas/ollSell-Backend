<?php

namespace Modules\Order\Imports;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Enums\OrderEnum;
use Illuminate\Support\Facades\Log;
use Modules\CoreData\Entities\Country;
use Modules\Order\Entities\PendingOrder;
use Modules\MasterCatalog\Entities\Product;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Order\Actions\PendingOrder\ValidationPendingOrdersAction;
use Modules\Order\Enums\PlatformEnum;

class PendingOrdersImport implements ToCollection, WithStartRow
{
    /**
     * Method model
     *
     * param array $row
     *
     * return void
     */
    protected $cityService;
    protected $service;

    /**
     * This is a constructor function that initializes three service objects.
     *
     * param CountryService countryService An instance of the CountryService class, which likely
     * provides functionality related to managing countries (e.g. retrieving a list of countries,
     * adding a new country, updating an existing country, etc.).
     * param CityService cityService An instance of the CityService class, which likely provides
     * functionality related to managing cities (e.g. creating, updating, deleting, and retrieving city
     * data).
     * param OrderService service An instance of the OrderService class, which is likely responsible
     * for handling orders in some way.
     */
    public function __construct($cityService, $service)
    {
        $this->cityService = $cityService;
        $this->service = $service;
    }

    /**
     * The function processes a collection of rows, validates the data, and stores the valid rows as
     * orders while storing the invalid rows as a report.
     *
     * param Collection rows A collection of rows from an Excel file that contains order data.
     */
    public function collection(Collection $rows)
    {
        $ValidationPending = App(ValidationPendingOrdersAction::class);
        $request = request();
        foreach ($rows as $row) {
            $items = $this->mapPrepareProduct($row);
            $country = $this->mapPrepareCountry($row[5]);
            $city = $this->cityService->search(new Request(['alias' => array_filter(explode(' ', $row[4]))]));
            $city_in_country = true;
            if ($country) {
                if ($city && $city->country_id != $country->id) {
                    $city_in_country = false;
                }
            }
            $paymentMethod = null;
            $paymentMethodType = strtolower($row[7]);
            if ($paymentMethodType == 'wallet') {
                $paymentMethod = 3;
            } else if ($paymentMethodType == 'cod') {
                $paymentMethod = 2;
            } else if ($paymentMethodType == 'online') {
                $paymentMethod = 1;
            }
            if ($paymentMethod == 1 && user()->DropshipperOptionCheck('convert_payment_online_to_wallet')) {
                $paymentMethod = 3;
            }
            $requestOrder = [
                'dropshipper_id' => user()->id,
                'customer_name' => $row[0],
                'customer_phone' => $row[1],
                'customer_address' => $row[2],
                'district' => $row[3],
                'country_id' => $country ? $country->id : null,
                'city_id' => $city->id ?? null,
                'city_in_country' => $city_in_country,
                'customer_city' => $row[4],
                'customer_country' => $row[5],
                'payment_method' => $paymentMethod,
                'items' => $items,
                'is_duplicated' => false,
                'invalid' => false,
                'duplicated_order_ids' => null,
                'source_platform' => PlatformEnum::getPleatform($row[6]),
                'created_platform' => PlatformEnum::WEBSITE_PLATFORM
            ];
            $requestOrder = $ValidationPending->allValidation($requestOrder);
            $data = $this->service->storePendingOrdersImport($request->merge($requestOrder));
        }
    }

    /**
     * The function startRow() in PHP returns the integer value 2.
     *
     * @return int The function `startRow()` is returning the integer value `2`.
     */
    public function startRow(): int
    {
        return 2;
    }

    public function mapPrepareProduct($row)
    {
        $sku = explode(",", $row[8]);
        $quantity = explode(",", $row[9]);
        $selling_price = explode(",", $row[10]);
        $maxCount = max(count($sku), count($quantity), count($selling_price));
        for ($i = 0; $i < $maxCount; $i++) {
            $item[] = [
                'sku' => isset($sku[$i]) ? trim($sku[$i]) : null,
                'quantity' => isset($quantity[$i]) ? $quantity[$i] : null,
                'selling_price' => isset($selling_price[$i]) ? $selling_price[$i] : null,
            ];
        }
        $itemArray = [];
        foreach ($item as $value) {
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
                        'selling_price' => $value['selling_price'] ?? null
                    ];
                }
            } else {
                $product = null;
                $itemArray[] = [
                    'sku' => $value['sku'] ?? null,
                    'product' => ($product) ? $product->id : null,
                    'productData' => ($product) ? $product : null,
                    'quantity' => $value['quantity'] ?? null,
                    'selling_price' => $value['selling_price'] ?? null
                ];
            }
        }
        return $itemArray;
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
