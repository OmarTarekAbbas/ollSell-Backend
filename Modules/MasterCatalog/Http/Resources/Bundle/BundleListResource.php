<?php

namespace Modules\MasterCatalog\Http\Resources\Bundle;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\MasterCatalog\Http\Resources\Bundle\BundleProductListResource;

//todo change

class BundleListResource extends JsonResource
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
        $bundleProducts = BundleProductListResource::collection($this->products);
        return [
            'id' => $this->id,
            'name' => $this->name->value ?? "",
            'description' => $this->description->value ?? "",
            'cost_price' => round($this->cost_price, 2) ?? 0,
            'quantity' => $this->quantity,
            'products' => $bundleProducts,
            'isCart' =>  (bool)$this->isCart(),
        ];
    }
}

