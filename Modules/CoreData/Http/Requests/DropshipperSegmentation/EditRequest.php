<?php

namespace Modules\CoreData\Http\Requests\DropshipperSegmentation;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Basic\Traits\validationRulesTrait;
use App\Rules\NotUsedBeforeSegmentation;
use Modules\CoreData\Entities\DropshipperSegmentation;

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
        
        $rules = $this->translationValidationRules(DropshipperSegmentation::class, DropshipperSegmentation::getValidationRules(),
        DropshipperSegmentation::translationKey(),$this->id,['name']);
        $rules['from'] = [
            'min:1',
            'numeric',
          new NotUsedBeforeSegmentation($this->id),
        ];
        $rules['to'] = [
           'numeric',
           'gt:from'
        ];
        return $rules;
      
    }
}
