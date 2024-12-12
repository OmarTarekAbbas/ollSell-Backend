<?php

namespace Modules\StoreIntegrations\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Store\Entities\DropshipperMappingOrder;
use Modules\Store\Entities\DropshipperMappingProduct;
use Modules\MasterCatalog\Http\Resources\Product\ProductListResource;

class DropshipperEcommerceResource extends JsonResource
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
        $productsCount = DropshipperMappingProduct::where('dropshipper_id', $this->dropshipper_id)->count();
        $ordersCount = DropshipperMappingOrder::where('dropshipper_id', $this->dropshipper_id)->count();

        return [
            'id' => $this->id,
            'dropshipper_id' => $this->dropshipper_id,
            'owner_id' => $this->owner_id,
            'store_id' => $this->store_id,
            'store_type' => $this->store_type,
            'phone' => $this->phone,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'role' => $this->role,
            'productsCount' => $productsCount,
            'ordersCount' => $ordersCount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
