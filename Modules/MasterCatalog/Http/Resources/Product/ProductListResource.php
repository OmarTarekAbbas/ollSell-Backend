<?php

namespace Modules\MasterCatalog\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Basic\Http\Resources\Media\mediaResource;
use Modules\CoreData\Http\Resources\Category\CategoryResource;
use Modules\CoreData\Http\Resources\TargetMarket\TargetMarketListResource;
//todo change

class ProductListResource extends JsonResource
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
        $usedAttributesOptions = [];
        return [
            'id' => $this->id,
            'name' => $this->name->value ?? "",
            'description' => $this->description->value ?? "",
            'cost_price' => round($this->cost_price, 2) ?? 0,
            'selling_price' => $this->calculator(),
            'profit' =>  $this->profitProduct(),
            'profitAmount' =>  $this->profitAmount(),
            'vat' =>  $this->vatProduct(),
            'commission' =>  $this->commission,
            'vat_commission' =>  $this->vat_commission,
            'supplier_price' =>  $this->supplier_price_cost,
            'isSelected' =>  (bool)$this->isSelected(),
            'isManual' =>  (bool)$this->isManual(),
            'isRecommended' =>  (bool)$this->is_recommended,
            'isDiscount' =>  (bool)$this->is_discount,
            'priceAfterDiscount' =>  $this->priceAfterDiscount ?? 0,
            'isFavorite' =>  (bool)$this->isFavorite(),
            'isCart' =>  (bool)$this->isCart(),
            'sku' => $this->sku,
            'size' => $this->size,
            'quantity' => $this->quantity,
            'weight' => $this->weight,
            'sizeText' => sizeText($this->size),
            'targt_market' => TargetMarketListResource::collection($this->targetMarket),
            'logo' =>    mediaResource::collection($this->logo),
            'thumbnail' =>    mediaResource::collection($this->thumbnail),
            'barcode' =>    $this->barcode,
            'attributes' => AttributeListResource::collection($this->attributes),
            'categories' => CategoryResource::collection($this->categories),
            'used_attributes_options' => $usedAttributesOptions,
            'move' =>   ($this->queryMappingProduct()?->move)? $this->queryMappingProduct()?->move :0,
            'variants' => [],
        ];
    }

    public function calculator() {
        $basePrice = $this->cost_price;
        return round(($basePrice), 2);
    }

    public function profitProduct() {
        $profitProduct = $this->queryProfitProduct();
        if($profitProduct)
        {
            return $profitProduct->profit;
        }
        return user()->profit;
    }

    public function profitAmount() {
        $basePrice = $this->is_discount ? $this->priceAfterDiscount : $this->cost_price;
        return round(($basePrice * ($this->profitProduct() / 100)), 2);
    }

    public function vatProduct() {
        $basePrice = $this->is_discount ? $this->priceAfterDiscount : $this->cost_price;
        return round(($basePrice * 0.15) + ($this->profitAmount() * 0.15), 2);
    }

    public function isSelected(){
        // Implement the logic for checking if the product is selected
        // For example, return a boolean value based on product attributes
        return $this->is_selected; // Example logic
    }

    public function isManual(){
        // Implement the logic for checking if the product is manually entered
        // For example, return a boolean value based on product attributes
        return $this->is_manual; // Example logic
    }

}

