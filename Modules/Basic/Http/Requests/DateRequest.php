<?php

namespace Modules\Basic\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Basic\Traits\ApiResponseTrait;

class DateRequest extends FormRequest
{
    use ApiResponseTrait;
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
            'fromDate' => 'nullable|date',
            'orderDateFrom' => 'nullable|date',
            'toDate' => 'nullable|date|after_or_equal:fromDate',
            'orderDateTo' => 'nullable|date|after_or_equal:orderDateFrom',
        ];
    }

    public function messages()
    {
        return [
            'fromDate.date' => 'Invalid From Date format.',
            'toDate.date' => 'Invalid To Date format.',
            'toDate.after_or_equal' => 'To Date must be equal to or greater than From Date.',
            'orderDateFrom.date' => 'Invalid From Date format.',
            'orderDateTo.date' => 'Invalid To Date format.',
            'orderDateTo.after_or_equal' => 'To Date must be equal to or greater than From Date.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->unKnowError(trans('orders.The to date must be a date after or equal to from date')));
    }
}
