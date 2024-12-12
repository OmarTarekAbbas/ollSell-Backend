<?php

namespace Modules\Supplier\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\MasterCatalog\Entities\Product;
use Modules\Basic\Traits\validationRulesTrait;
use App\Rules\UniqueSku;
//todo change
class StoreProductRequest extends FormRequest
{
    use validationRulesTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = $this->translationValidationSingleRules(Product::class, [
            'supplier_price_cost' => 'required|numeric|gt:0',
            'quantity' => 'required|numeric|min:0',
            'weight' => 'required|numeric|gt:0',
            'status' => 'required',
            'warehouse_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'barcode' => 'nullable|min:5|max:50|regex:/^[A-Za-z0-9-]+$/',
        ], Product::translationKey(), $this->id, unique: ['name'], languageCode: ['ar']);
        $rules['sku'] = ['required', new UniqueSku];
        $rules['thumbnail'] =  ['required', 'image',];
        $rules['logo'] =  ['required',];
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    public function messages(): array
    {
        return [
            'cost_price.required'       => 'Price is required.',
            'name.en.required'          => 'English name is required.',
            'name.en.string'            => 'English name must be valid string.',
            'name.ar.required'          => 'Arabic name is required.',
            'name.ar.string'            => 'Arabic name must be valid string.',
            'description.en.required'   => 'English description is required.',
            'description.en.string'     => 'English description must be valid string.',
            'description.ar.required'   => 'Arabic description is required.',
            'description.ar.string'     => 'Arabic description must be valid string.',
        ];
    }
}
