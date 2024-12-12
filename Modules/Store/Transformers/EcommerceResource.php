<?php

namespace Modules\Store\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class EcommerceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'name' => $this->name,
            'seeded' => $this->seeded,
            'dropshipper_id' => $this->dropshipper_id,
            'saas_user_id' => $this->saas_user_id,
        ];
    }
}