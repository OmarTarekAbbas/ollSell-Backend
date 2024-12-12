<?php

namespace Modules\MasterCatalog\Http\Resources\Favorite;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\MasterCatalog\Http\Resources\Product\ProductListResource;

class FavoriteResource extends JsonResource
{
    /**
     * The toArray() method is used to convert the resource into an array
     * 
     * param request The incoming HTTP request.
     * 
     * return The id, dropshipper_id, and product_id of the model.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'dropshipperId' => $this->dropshipper_id,
            'products' =>  new ProductListResource($this->product),
        ];
    }
}
