<?php

namespace Modules\Order\Http\Requests\PendingOrder;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Basic\Traits\ApiResponseTrait;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\Order\Entities\PendingOrder;
use Modules\Order\Actions\PendingOrder\ValidationPendingOrdersAction;
use Illuminate\Http\Request;

use Modules\CoreData\Entities\Country;
use Modules\CoreData\Service\CityService;
use Modules\MasterCatalog\Entities\Product;
class EditRequest extends FormRequest
{
    use ApiResponseTrait, validationRulesTrait;
    /**
     * Determine if the User is authorized to make this request.
     *
     * return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * return array
     */
    public function rules()
    {
        return PendingOrder::getValidationEditRules();
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
          $requestOrder=  $this->somethingElseIsInvalid();
            if ($requestOrder['invalid']) {
                $validator->errors()->add('invalid', @json_decode($requestOrder['message'])[0]);
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiValidation($validator->errors()));
    }

    public function somethingElseIsInvalid(){
        $validationPending = App(ValidationPendingOrdersAction::class);
        $request = request();
        $items = $this->mapPrepareProduct($request->items);
        $country = $this->mapPrepareCountry($request->customer_country);
        $city = app(CityService::class)->search(new Request(['alias' => $request->customer_city]));
        $requestOrder = [
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'district' => $request->district,
            'customer_location' => $request->customer_location,
            'country_id' => $country->id ?? null,
            'city_id' => $city->id ?? null,
            'customer_city' => $request->customer_city,
            'customer_country' => $request->customer_country,
            'payment_method' => $request->payment_method ,
            'items' => $items,
            'is_duplicated' => false,
            'invalid' => false,
            'duplicated_order_ids' => null,
        ];
        $requestOrder = $validationPending->allValidation($requestOrder);
        return $requestOrder;

    }

    public function mapPrepareProduct($items)
    {
        $itemArray = [];
        foreach ($items as $item) {

            $product =  Product::where('sku', $item['sku'])->first();

            $itemArray[] = [
                'sku' => $item['sku'],
                'product' => ($product) ? $product->id  : null,
                'productData' => ($product) ? $product  : null,
                'quantity' => $item['quantity'],
                'selling_price' => $item['selling_price'],
            ];
        }
        return $itemArray;
    }
    public function mapPrepareCountry($row)
    {
        $code = 'sa';
        if ($row == 'KSA') {
            $code = 'sa';
        }

        if ($row == 'UAE') {
            $code = 'ae';
        }

        if ($row == 'EGY') {
            $code = 'eg';
        }

        return  Country::where('code', $code)->first();
    }

    
}
