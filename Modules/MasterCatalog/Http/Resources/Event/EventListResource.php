<?php

namespace Modules\MasterCatalog\Http\Resources\Event;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Basic\Http\Resources\Media\mediaResource;
use Modules\MasterCatalog\Http\Resources\Product\ProductListResource;

//todo change
class EventListResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
            'status' => $this->status,
            'image' =>    mediaResource::collection($this->image),
        ];
    }
}
