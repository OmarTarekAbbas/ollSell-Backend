<?php

namespace Modules\Acl\Http\Requests\Dropshipper\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Basic\Traits\ApiResponseTrait;
use Modules\Basic\Traits\validationRulesTrait;

class QuestionnaireRequest extends FormRequest
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
            'onboarding_questionnaire_number' => 'required|max:5',
            'is_old_dropshipper' => 'required_if:onboarding_questionnaire_number,==,1|in:0,1',
            'number_years_dropshipper' => 'required_if:onboarding_questionnaire_number,==,2|in:1,2,3,4',
            'questionnaire_target_markets' => 'required_if:onboarding_questionnaire_number,==,3|exists:target_markets,id',
            'questionnaire_dropshipper_social' => 'required_if:onboarding_questionnaire_number,==,4',
            'cost_month_dropshipper' => 'required_if:onboarding_questionnaire_number,==,5',
            'questionnaire_category' => 'required_if:onboarding_questionnaire_number,==,6|exists:onboarding_categories,id'

        ];
    }
    public function messages()
    {
        return [
            'questionnaire_dropshipper_social.required_if'=>trans('app.questionnaire_dropshipper_social'),
            'is_old_dropshipper.required_if'=>trans('app.is_old_dropshipper'),
            'number_years_dropshipper.required_if'=>trans('app.number_years_dropshipper'),
            'questionnaire_target_markets.required_if'=>trans('app.questionnaire_target_markets'),
            'cost_month_dropshipper.required_if'=>trans('app.cost_month_dropshipper'),
            'questionnaire_category.required_if'=>trans('app.questionnaire_category'),
            'onboarding_questionnaire_number'=>trans('app.onboarding_questionnaire_number')];
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
