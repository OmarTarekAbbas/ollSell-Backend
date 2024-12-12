<?php

namespace Modules\Order\Actions\Order;

use Illuminate\Http\Request;
use Modules\CoreData\Entities\Country;
use Modules\CoreData\Service\CityService;
use Modules\MasterCatalog\Entities\Product;
use Modules\Order\Service\PendingOrderImportService;
use Modules\Order\Actions\PendingOrder\ValidationPendingOrdersAction;

class UpdatePendingOrderAction
{
    protected $id;
    protected $request;

    public function __construct(Request $request, $id)
    {
        $this->id = $id;
        $this->request = $request;
    }

    public function execute()
    {
        $validationPending = App(ValidationPendingOrdersAction::class);
        $request = request();
        $items = $this->mapPrepareProduct($this->request);
        $country = $this->mapPrepareCountry($this->request->customer_country);
        $city = app(CityService::class)->search(new Request(['alias' => $this->request->customer_city]));
        $city_in_country = true;
        if($country)
        {
            if($city && $city->country_id != $country->id)
            {
                $city_in_country = false;
            }
        }
        $paymentMethod = $this->request->payment_method;
        if($paymentMethod == 1 && user()->DropshipperOptionCheck('convert_payment_online_to_wallet'))
        {
            $paymentMethod = 3;
        }
        $requestOrder = [
            'customer_name' => $this->request->customer_name,
            'customer_phone' => $this->request->customer_phone,
            'customer_address' => $this->request->customer_address,
            'district' => $this->request->district,
            'source_platform' => $this->request->source_platform,
            'country_id' => $country->id ?? null,
            'city_id' => $city->id ?? null,
            'city_in_country' => $city_in_country,
            'customer_city' => $this->request->customer_city,
            'customer_country' => $this->request->customer_country,
            'payment_method' => $paymentMethod,
            'items' => $items,
            'is_duplicated' => false,
            'invalid' => false,
            'duplicated_order_ids' => null,
        ];
        $requestOrder = $validationPending->allValidation($requestOrder);
        return app(PendingOrderImportService::class)->update($request->merge($requestOrder), $this->id);
    }

    public function mapPrepareProduct($request)
    {
        $itemArray = [];
        foreach($request->items as $value)
        {
            $product = Product::where('sku', $value['sku'])->first();
            if($product)
            {
                $can = true;
                $ids = $product->product_dropshippers->pluck('dropshipper_id');
                if($ids->count())
                {
                    if(!in_array(user()->id, $ids->toArray()))
                    {
                        $can = false;
                    }
                }
                if($product->isApproved && $product->status && $can)
                {
                    $itemArray[] = [
                        'sku' => $value['sku'] ?? null,
                        'product' => ($product) ? $product->id : null,
                        'productData' => ($product) ? $product : null,
                        'quantity' => $value['quantity'] ?? null,
                        'selling_price' => $value['selling_price'] ?? null,
                    ];
                }else{
                    $product = null;
                    $itemArray[] = [
                        'sku' => $value['sku'] ?? null,
                        'product' => ($product) ? $product->id : null,
                        'productData' => ($product) ? $product : null,
                        'quantity' => $value['quantity'] ?? null,
                        'selling_price' => $value['selling_price'] ?? null,
                    ];
                }
            }else
            {
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
        if($row == 'KSA')
        {
            $code = 'sa';
        }
        if($row == 'UAE')
        {
            $code = 'ae';
        }
        if($row == 'EGY')
        {
            $code = 'eg';
        }
        return Country::where('code', $code)->first();
    }
}
