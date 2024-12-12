<?php

namespace Modules\Order\Http\Requests\SaasOrder;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * return array
     */
    public function rules()
    {
        return [
            'paymentMethod' => 'required|integer',
            'items' => 'required|array',
            'items*' => 'required|exists:products,id',
            'customerName' => 'required|min:3|max:50',
            'customerPhone' => 'required|max:50',
            'customerAddress' => 'required',
            'customerLocation' => 'nullable|url',
            'customerCity' => 'required',
            'phone_code' => 'required|integer',
            'customerCountry' => 'required|exists:countries,id|max:10',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiValidation($validator->errors()));
    }
}
