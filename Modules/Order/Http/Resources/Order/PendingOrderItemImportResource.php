<?php

namespace Modules\Order\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CoreData\Http\Resources\City\CityResource;
use Modules\CoreData\Http\Resources\Country\CountryListResource;
use Modules\MasterCatalog\Http\Resources\Product\ProductListResource;

class PendingOrderItemImportResource extends JsonResource
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
            'sku' => $this->sku,
            'quantity' => $this->quantity,
            'selling_price' => $this->selling_price,
        ];
    }
}