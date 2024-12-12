<?php

namespace Modules\Supplier\Http\Requests;

use Modules\CoreData\Entities\Category;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Basic\Traits\validationRulesTrait;
//todo change
class CategorySuggestRequest extends FormRequest
{
    use validationRulesTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->translationValidationRules(Category::class, [], ['name']);

    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'name.en.unique' => 'The English name has already been taken or suggested.',
            'name.ar.unique' => 'The Arabic name has already been taken or suggested.',
        ];
    }
}
