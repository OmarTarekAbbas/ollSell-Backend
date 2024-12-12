<?php

namespace Modules\Order\Http\Resources\Order;

use Modules\Order\Entities\Order;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CoreData\Http\Resources\City\CityResource;
use Modules\Order\Http\Resources\Order\SimplifiedOrderResource;
use Modules\CoreData\Http\Resources\Country\CountryListResource;
use Modules\MasterCatalog\Http\Resources\Product\ProductListResource;

class PendingOrderImportResource extends JsonResource
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
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_address' => $this->customer_address,
            'district' => $this->district,
            'customer_city' =>  $this->customer_city,
            'customer_country' =>  $this->customer_country,
            'city' =>  new CityResource($this->city),
            'country' => new CountryListResource($this->country),
            'source_platform' => $this->source_platform,
            'payment_method' => $this->payment_method ? (int)$this->payment_method : null,
            'duplicated_order_ids' => $this->duplicated_order_ids,
            'duplicated_orders' => $this->getDuplicatedOrders($this->duplicated_order_ids),
            'is_duplicated' => $this->is_duplicated,
            'invalid' => $this->invalid,
            'items' =>  PendingOrderItemImportResource::collection($this->pendingOrderItems),
            'message' => json_decode($this->message),
        ];
    }

    private function getDuplicatedOrders($duplicated_order_ids)
    {
        // Decode the string into an array
        $decodedIds = json_decode($duplicated_order_ids, true);

        // If decoding fails or it's not an array, return null
        if (!is_array($decodedIds)) {
            return $decodedIds;
            return null;
        }

        $formattedOrders = [];
        foreach ($decodedIds as $id) {
            $order = Order::find($id);
            if ($order) {
                $formattedOrders[] = new SimplifiedOrderResource($order); // Use simplified resource
            }
        }

        return $formattedOrders;
    }
}
