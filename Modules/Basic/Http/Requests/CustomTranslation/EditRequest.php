<?php

namespace Modules\Basic\Http\Requests\CustomTranslation;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Basic\Entities\CustomTranslation;
use Modules\Basic\Traits\validationRulesTrait;

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
        $rules= CustomTranslation::getValidationRules();
        $rules['key'] = $rules['key'].',key,'.$this->id.',id';
        $rules = $this->translationValidationRules(CustomTranslation::Class,$rules,CustomTranslation::translationKey(),$this->id);
        return $rules;
    }

}
