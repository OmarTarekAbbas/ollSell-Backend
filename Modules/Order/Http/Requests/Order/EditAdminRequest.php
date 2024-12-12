<?php

namespace Modules\Order\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Basic\Traits\validationRulesTrait;

class EditAdminRequest extends FormRequest
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
    protected function prepareForValidation()
    {
        $this->merge(['customerPhone' => $this->handlePhoneKSA($this->customerPhone)]);
        if(!$this->checkFake($this->customerPhone))
        {
            $this->merge(['is_fake' => $this->checkFake($this->customerPhone)]);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customerName' => 'required|min:3|max:50',
            'customerPhone' => 'required|regex:/^05\d{8}$/',
            'customerAddress' => 'required',
            'customerLocation' => 'nullable|url',
            'customerCity' => 'required|exists:cities,id',
            'phone_code' => 'required|integer',
            'country_id' => 'required|exists:countries,id',
            'is_fake' => 'required'
        ];
    }
    public function messages()
    {
        return ['is_fake'=>trans('order.fake')];
    }
}
