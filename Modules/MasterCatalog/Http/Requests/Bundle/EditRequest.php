<?php

namespace Modules\MasterCatalog\Http\Requests\Bundle;

use App\Rules\UniqueSku;
use Illuminate\Foundation\Http\FormRequest;
use Modules\MasterCatalog\Entities\Bundle;
use Modules\Basic\Traits\validationRulesTrait;


class EditRequest extends FormRequest
{
    use validationRulesTrait;

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
    {//todo change

        $bundle_products = $this->bundle_products;
        $bundle_products ['count'] = count($bundle_products['product_id'] ?? []);
        $this->merge(['bundle_products'=>$bundle_products]);
        $rules = $this->translationValidationRules(Bundle::class, Bundle::getValidationRules(),
        Bundle::translationKey(),$this->id, ['name']);
        $rules['bundle_products']=['required','array'];
        $rules['bundle_products.product_id.*']=['required','numeric'];
        $rules['bundle_products.count']=['required','numeric','min:2','max:999999'];
        $rules['sku'] = ['required', new UniqueSku($this->id)];
       return $rules;
    }

    public function messages()
    {
        return [
            'bundle_products.product_id.*.required'=>trans('validation.bundle_products_product_id'),
            'bundle_products.count.numeric'=>trans('validation.bundle_products_count'),
        ];
    }
}
