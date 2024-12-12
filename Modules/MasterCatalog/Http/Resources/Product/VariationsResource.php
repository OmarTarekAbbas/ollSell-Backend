<?php

namespace Modules\MasterCatalog\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
//todo change
class VariationsResource extends JsonResource
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
            'price' => $this->price,
            'quantity' => $this->quantity,
            'sku' => $this->sku,
            'values' =>  $this->productVariantValue->map(function($value){
                return $value->attributeOption->name;
            })->implode('/'),
            'variants_values' =>  VariationsValueResource::collection($this->productVariantValue)
      
        ];
    }
}
// implode(', ', $this->values->pluck('name')) 