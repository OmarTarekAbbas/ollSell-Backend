<?php

namespace Modules\Acl\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Basic\Traits\validationRulesTrait;
use App\Models\User;
class ResetPasswordRequest extends FormRequest
{
    use validationRulesTrait;
    /**
     * Get the validation rules that apply to the request.
     *
     * return array
     */
    public function rules()
    {

        return $this->translationValidationRules(User::class,User::$getValidationEmail,User::translationKey());

    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * return bool
     */
    public function authorize()
    {
        return true;
    }
}
