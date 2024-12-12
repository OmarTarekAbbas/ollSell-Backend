<?php

namespace Modules\MasterCatalog\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Basic\Http\Resources\Media\mediaResource;
use Modules\CoreData\Http\Resources\Category\CategoryResource;
use Modules\CoreData\Http\Resources\TargetMarket\TargetMarketListResource;

//todo change

class ProductResource extends JsonResource
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
            'description' => $this->description->value ?? "",
            'cost_price' => round($this->cost_price, 2) ?? 0,
            'selling_price' => $this->calculator(),
            'vat' =>  $this->vatProduct(),
            'isFavorite' =>  (bool)$this->isFavorite(),
            'isCart' =>  (bool)$this->isCart(),
            'sku' => $this->sku,
            'quantity' => $this->quantity,
            'logo' =>    mediaResource::collection($this->logo),
            'thumbnail' =>    mediaResource::collection($this->thumbnail),
            'profit' =>  $this->profitProduct(),
        ];
    }
}

