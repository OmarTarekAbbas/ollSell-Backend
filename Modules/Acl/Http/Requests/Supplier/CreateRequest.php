<?php

namespace Modules\Acl\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Acl\Entities\Supplier;
use Modules\Basic\Traits\validationRulesTrait;

class CreateRequest extends FormRequest
{
    use validationRulesTrait;

    /**
     * Determine if the Supplier is authorized to make this request.
     *
     * return bool
     */
    public function authorize(): bool
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
        return Supplier::getValidationRules();
    }

}
