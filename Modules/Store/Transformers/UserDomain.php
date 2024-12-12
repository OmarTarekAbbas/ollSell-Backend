<?php

namespace Modules\Store\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
//todo change
class UserDomain extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * param  \Illuminate\Http\Request
     * return array
     */
    public function toArray($request)
    {
        return [
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'username' => $this->username,
            'name' => $this->name,
        ];
    }
}
