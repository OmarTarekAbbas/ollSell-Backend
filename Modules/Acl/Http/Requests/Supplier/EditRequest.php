<?php

namespace Modules\Acl\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Acl\Entities\Supplier;
use Modules\Basic\Traits\validationRulesTrait;

class EditRequest extends FormRequest
{
    use validationRulesTrait;

    /**
     * Determine if the Supplier is authorized to make this request.
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
        $rules = Supplier::getValidationRules();
        $rules['email'] = $rules['email'] . ',email,' . $this->route()->parameter('supplier') . ',id';
        return $rules;
    }

}
