<?php

namespace Modules\CoreData\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\CoreData\Entities\City;

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
        $rules = $this->translationValidationRules(City::Class, City::getValidationRules(), City::translationKey(), $this->id);
        return $rules;
    }
}
