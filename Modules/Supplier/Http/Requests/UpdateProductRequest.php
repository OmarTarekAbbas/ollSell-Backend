<?php

namespace Modules\Supplier\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\MasterCatalog\Entities\Product;
use Modules\Basic\Traits\validationRulesTrait;
use App\Rules\UniqueSku;
//todo change
class UpdateProductRequest extends FormRequest
{
    use validationRulesTrait;
    /**
     * Determine if the User is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  $this->translationValidationSingleRules(Product::class, [
            'supplier_price_cost' => 'required|numeric|gt:0',
            'quantity' => 'required|numeric|min:0',
            'weight' => 'required|numeric|gt:0',
            'status' => 'required',
            'warehouse_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'barcode' => 'nullable|min:5|max:50|regex:/^[A-Za-z0-9-]+$/',
        ], Product::translationKey(), $this->id, unique: ['name'], languageCode: ['ar']);
        $rules['thumbnail'] =  ['nullable', 'image',];
        $rules['sku'] = ['required', new UniqueSku($this->id)];
        $rules['logo'] =  ['nullable',];
        return $rules;
    }
}
