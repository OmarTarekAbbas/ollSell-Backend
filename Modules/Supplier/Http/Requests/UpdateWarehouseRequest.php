<?php

namespace Modules\Supplier\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Supplier\Entities\Warehouse;
//todo change
class UpdateWarehouseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('warehouse') ?? $this->id;

        return [
            'name' => 'required|max:255|unique:warehouses,name,' . $id,
            'country_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'address' => 'required|max:255',
            'district' => 'required|max:50',
            'location' => 'nullable|url',
        ];
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

    public function messages()
    {
        return [
            'city_id.required' => 'Please select a city.',
            'city_id.numeric' => 'Please select a city.',
        ];
    }
}
