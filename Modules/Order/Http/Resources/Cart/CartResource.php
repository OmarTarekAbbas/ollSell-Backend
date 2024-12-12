<?php

namespace Modules\Order\Http\Resources\Cart;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\MasterCatalog\Http\Resources\Product\ProductResource;
use Modules\MasterCatalog\Http\Resources\Bundle\BundleResource;
use Modules\Order\Actions\OrderItem\CreateOrderItemAction;
use Illuminate\Support\Facades\Log;

class CartResource extends JsonResource
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
        

        $item['quantity']=$this->quantity;
        $item['sellingPrice']=$this->selling_price;
        
        return [
            'id' => $this->id,
            'product' => $this->product ? new ProductResource($this->product) : null,
            'bundle' => $this->bundle ? new BundleResource($this->bundle) : null,
            'quantity' => $this->quantity,
            'netProfit'=>round(App(CreateOrderItemAction::class)->netProfit($this->product ?? $this->bundle, $item),2),
            'vatProfit'=>round(App(CreateOrderItemAction::class)->vatProfit($this->product ?? $this->bundle, $item),2),
            'selling_price' => round($this->selling_price, 2) ?? 0,
        ];
    }
}
