<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WMSWebhookRequest extends FormRequest
{ //todo change
    /**
     * Determine if the user is authorized to make this request.
     *
     * return bool
     */
    public function authorize()
    {
       // return $this->header('Authorization') === config('services.salla.webhook_secret');
       return true;
    }

    public function rules()
    {
        return [
            'event_name'    => ['required'],
            'data'     => ['required'],
        ];
    }
}
