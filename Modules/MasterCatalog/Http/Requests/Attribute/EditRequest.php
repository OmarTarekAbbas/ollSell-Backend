<?php

namespace Modules\MasterCatalog\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\MasterCatalog\Entities\Attribute;

class EditRequest extends FormRequest
{
    use validationRulesTrait;
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
        $rule = Attribute::getValidationRules();
        $rule['name'] = $rule['name'] . ',name,' . $this->id . ',id';
        return $rule;
    }

}
