<?php

namespace Modules\MasterCatalog\Http\Resources\Bundle;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Basic\Http\Resources\Media\mediaResource;
use Modules\MasterCatalog\Http\Resources\Bundle\BundleProductListResource;

// use Modules\MasterCatalog\Traits\CalculatorTrait;
//todo change
class BundleResource extends JsonResource
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
            'name' => $this->name->value ?? "", // Ensure 'name' is defined in the model
            'description' => $this->description->value ?? "",
            'status' => $this->status,
            'sku' => $this->sku,
            'quantity' => $this->quantity,
            'discount' => $this->discount,
            'cost_price' => $this->cost_price,
            'products' => BundleProductListResource::collection($this->products),
            'isCart' =>  (bool)$this->isCart(),
        ];
    }
}
