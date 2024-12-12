<?php

namespace Modules\Logistics\Http\Requests\ShippingCompany;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\Logistics\Entities\ShippingCompany;

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
    {
        $rules =  ShippingCompany::getValidationRules();
        $rules['email'] = $rules['email'] . ',email,' . request('id') . ',id';
        $rules['phone'] = $rules['phone'] . ',phone,' .  request('id')  . ',id';
        $rules['address'] = $rules['address'] . ',address,' .  request('id') . ',id';
        $rules['name'] = $rules['name'] . ',name,' .  request('id') . ',id';
        return $rules;
    }
}
