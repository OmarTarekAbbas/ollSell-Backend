<?php

namespace Modules\Acl\Http\Requests\Dropshipper\Api;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Basic\Traits\ApiResponseTrait;
use Modules\Basic\Traits\validationRulesTrait;

class CreateMegaDropshipperRequest extends FormRequest
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
        //todo change
        return [
            'email' => 'required|email|unique:dropshippers',
            'phone' => 'required|string|digits_between:10,15|unique:dropshippers',
            'password' => 'required|min:8|confirmed',
            'target_market' => 'required|array|exists:target_markets,id',
        ];
    }
}
