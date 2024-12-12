<?php

namespace Modules\Order\Http\Requests\FollowUp;

use Modules\Basic\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Modules\Basic\Traits\validationRulesTrait;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveFollowUpRequest extends FormRequest
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
            'activity_type' => 'required|string|max:50',
            'title' => 'nullable|string',
            'content' => 'required|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiValidation($validator->errors()));
    }
}
