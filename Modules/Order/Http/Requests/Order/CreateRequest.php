<?php

namespace Modules\Order\Http\Requests\Order;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Basic\Traits\ApiResponseTrait;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\Order\Entities\Order;

class CreateRequest extends FormRequest
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
     * return array
     */
    public function rules()
    {
        return Order::getValidationRules();
    }
    public function messages()
    {
        return ['is_fake'=>trans('order.fake')];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiValidation($validator->errors()));
    }
}
