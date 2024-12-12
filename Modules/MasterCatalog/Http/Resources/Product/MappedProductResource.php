<?php

namespace Modules\MasterCatalog\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Basic\Http\Resources\Media\mediaResource;
use Modules\CoreData\Http\Resources\Category\CategoryResource;
use Modules\CoreData\Http\Resources\TargetMarket\TargetMarketListResource;

//todo change

class MappedProductResource extends JsonResource
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
            'product' => new ProductListResource($this->product),
            'product_id' => $this->product_id,
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
            'created_at' => $this->created_at,
            'selling_price' => $this->selling_price,
            'status' => $this->move,
            'store' => $this->model_type,
        ];
    }
}

