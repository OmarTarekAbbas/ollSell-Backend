<?php

namespace Modules\MasterCatalog\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;


class AttributeListResource extends JsonResource
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
            'options' => AttributeOptionListResource::collection($this->options)
        ];
    }
}
