<?php

namespace Modules\MasterCatalog\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Basic\Http\Resources\Media\mediaResource;
//todo change

class RelatedProductListResource extends JsonResource
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
        $data = $this->related_product;
        $usedAttributesOptions = [];
        return [
            'id' => $data->id,
            'name' => $data->name->value ?? "",
            'cost_price' => round($data->cost_price, 2) ?? 0,
            'selling_price' => $data->calculator(),
            'profit' =>  $data->profitProduct(),
            'profitAmount' =>  $data->profitAmount(),
            'vat' =>  $data->vatProduct(),
            'commission' =>  $data->commission,
            'vat_commission' =>  $data->vat_commission,
            'supplier_price' =>  $data->supplier_price_cost,
            'isFavorite' =>  (bool)$data->isFavorite(),
            'isManual' =>  (bool)$data->isManual(),
            'isRecommended' =>  (bool)$data->is_recommended,
            'isDiscount' =>  (bool)$data->is_discount,
            'priceAfterDiscount' =>  $data->priceAfterDiscount ?? 0,
            'sku' => $data->sku,
            'quantity' => $data->quantity,
            'logo' =>    mediaResource::collection($data->logo),
            'thumbnail' =>    mediaResource::collection($data->thumbnail),
            'attributes' => AttributeListResource::collection($data->attributes),
            'used_attributes_options' => $usedAttributesOptions,
            'move' =>   ($data->queryMappingProduct()?->move)? $data->queryMappingProduct()?->move :0,
            'variants' => [],
        ];
    }
}