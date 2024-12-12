<?php

namespace Modules\MasterCatalog\Http\Resources\Bundle;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Basic\Http\Resources\Media\mediaResource;

//todo change

class BundleProductListResource extends JsonResource
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
            'count' => $this->count,
            'cost_price' => $this->product->cost_price,
            'product_id' => $this->product->id,
            'name' => $this->product->name->value ?? "",
            'sku' => $this->product->sku,
            'thumbnail' => mediaResource::collection($this->product->thumbnail),
        ];
    }
}

