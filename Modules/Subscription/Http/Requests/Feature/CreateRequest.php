<?php

namespace Modules\Subscription\Http\Requests\Feature;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\Subscription\Entities\Feature;

class CreateRequest extends FormRequest
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
        return $this->translationValidationRules(Feature::class, Feature::getValidationRules(), Feature::translationKey());
    }
}
