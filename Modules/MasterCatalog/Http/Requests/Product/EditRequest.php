<?php

namespace Modules\MasterCatalog\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Modules\MasterCatalog\Entities\Product;
use Modules\Basic\Traits\validationRulesTrait;
use App\Rules\PriceAfterDiscountYoungerThanPrice;
use App\Rules\UniqueSku;

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
        $rules = $this->translationValidationSingleRules(Product::class, Product::getValidationRules(),
            Product::translationKey(), $this->id, ['name'], languageCode: ['ar']);
        $rules['priceAfterDiscount'] = [
            'nullable',
            'required_if:is_discount,1',
            'min:1',
            'numeric',
            new PriceAfterDiscountYoungerThanPrice($this->input('supplier_price_cost') ?? $this->input('cost_price')),
        ];
        $rules['sku'] = ['required', new UniqueSku($this->id)];
        $rules['thumbnail'] = ['nullable', 'image'];
        $rules['logo'] = ['nullable'];
        $rules['related_products'] = ['nullable','array','exists:products,id'];

        $rules['variants.*.sku'] = ['required_if:has_variants,1',];
        return $rules;
    }

    public function messages()
    {
        return [
            'sku.required' => 'SKU is required.',
            'sku.unique' => 'This SKU is already taken.',
            'name.required' => 'The name is required.',
            'name.string' => 'The name must be string.',
            'description.required' => 'The description is required.',
            'description.string' => 'The description must be string.',
        ];
    }
}
