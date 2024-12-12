<?php

namespace Modules\CoreData\Http\Requests\Country;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\CoreData\Entities\Country;

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
        $rules = $this->translationValidationRules(Country::Class, Country::getValidationRules(), Country::translationKey(), $this->id);
        $rules['order'] = $rules['order'] . ',order,' . $this->id . ',id';
        $rules['code'] = $rules['code'] . ',code,' . $this->id . ',id';
        return $rules;
    }
}
