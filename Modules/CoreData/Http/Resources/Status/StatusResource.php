<?php

namespace Modules\CoreData\Http\Resources\Status;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
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
            'name' => $this->name->value ?? "",
            'title' => getStatusTitle($this->id),
            'description' => getStatusDescription($this->id),
            'color' => getStatusColor($this->id),
        ];
    }
}
