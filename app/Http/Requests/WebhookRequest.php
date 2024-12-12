<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebhookRequest extends FormRequest
{ //todo change
    /**
     * Determine if the user is authorized to make this request.
     *
     * return bool
     */
    public function authorize()
    {
        return $this->header('Authorization') === setting('SALLA_WEBHOOK_SECRET');
    }

    public function rules()
    {
        return [
            'event'    => ['required'],
            'merchant' => ['required'],
            'data'     => ['required'],
        ];
    }
}
