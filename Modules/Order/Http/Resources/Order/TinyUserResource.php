<?php

namespace Modules\Order\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class TinyUserResource extends JsonResource
{
    /**
     * It returns the data in the form of an array.
     * 
     * param request The incoming request.
     * 
     * return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? $this->store_name,
            'email' => $this->email,
            'avatar' => $this->avatar ? getFile($this->avatar->file ?? null, pathType()['ip'], getFileNameServer($this->avatar)) : asset('dashboard') . '/assets/media/svg/avatars/blank.svg',
        ];
    }
}
