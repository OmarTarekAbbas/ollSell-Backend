<?php

namespace Modules\Finance\Http\Requests\WithdrawalRequest\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Acl\Entities\DropshipperPayment;
use Modules\Basic\Traits\ApiResponseTrait;
use Modules\Basic\Traits\validationRulesTrait;

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
        if ($this->dropshipper_payment_id == 0) {
            $rule = [
                'bank_name' => 'required|string|max:50',
                'bank_address' => 'required|string|max:50',
                'swift_number' => 'required|string|max:50|unique:dropshipper_payments',
                'beneficiary_name' => 'required|string|max:50',
                'beneficiary_address' => 'required|string|max:50',
                'beneficiary_mobile' => 'required|string|max:50',
                'currency' => 'required',
                'account_number' => 'required|unique:dropshipper_payments',
                'iban' => 'required',
                'amount' => 'required|numeric|min:50',
            ];
            if ($this->swift_no) {
                $this->merge(['swift_number' => $this->swift_no]);
                $rule['swift_no'] = 'required|string|max:50';
            }
            $this->validate($rule);
            $this->merge(['dropshipper_id' => user()->id]);
            $dropshipper_payment = DropshipperPayment::create($this->all());
            $this->merge(['dropshipper_payment_id' => $dropshipper_payment->id]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * return array
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:50',
            'dropshipper_payment_id' => 'required|exists:dropshipper_payments,id'
        ];
    }

    /**
     * This function throws an exception with validation errors in an API format if validation fails.
     *
     * param Validator validator  is an instance of the Validator class, which is
     * responsible for validating input data based on a set of rules defined in the validation rules
     * array. It checks if the input data meets the specified rules and returns an error message if it
     * fails to do so.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiValidation($validator->errors()));
    }
}
