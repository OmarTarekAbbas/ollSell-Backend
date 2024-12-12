<?php

namespace Modules\Acl\Http\Requests\Login\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Acl\Entities\Dropshipper;
use Modules\Basic\Traits\ApiResponseTrait;

class LoginRequest extends FormRequest
{
    use ApiResponseTrait;
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
        return Dropshipper::getValidationRulesLogin();
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
