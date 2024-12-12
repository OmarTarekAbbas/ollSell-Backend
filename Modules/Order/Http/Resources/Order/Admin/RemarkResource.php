<?php

namespace Modules\Order\Http\Resources\Order\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

class RemarkResource extends JsonResource
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
            'name' => $this->name,
            'sub_status_id' => $this->sub_status_id,
            'sub_status' => $this->subStatus?->name,
        ];
    }
}
