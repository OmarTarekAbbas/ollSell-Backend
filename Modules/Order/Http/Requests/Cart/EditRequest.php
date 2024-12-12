<?php

namespace Modules\Order\Http\Requests\Cart;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Basic\Traits\ApiResponseTrait;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\Order\Entities\Cart;
use App\Rules\SellingBriceBiggerCostPrice;

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
        $rules= Cart::getValidationRules();
        $selling_price = Cart::find($this->id)->selling_price;
        if($selling_price != $this->selling_price)
        {
            $rules['selling_price'] = ['required', new SellingBriceBiggerCostPrice($this->input('bundle_id'),$this->input('product_id'),$this->input('selling_price'))];
        }
        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiValidation($validator->errors()));
    }
}
