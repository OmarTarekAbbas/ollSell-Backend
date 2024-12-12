<?php

namespace Modules\Supplier\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Supplier\Entities\Warehouse;
//todo change
class WarehouseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * return array
     */
    public function rules()
    {
        return Warehouse::getValidationRules();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
{
    return [
        'city_id.required' => 'Please select a city.',
        'city_id.numeric' => 'Please select a city.',
    ];
}
}
