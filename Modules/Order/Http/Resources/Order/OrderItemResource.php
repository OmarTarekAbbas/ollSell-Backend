<?php

namespace Modules\Order\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\MasterCatalog\Http\Resources\Bundle\BundleListResource;
use Modules\MasterCatalog\Http\Resources\Product\ProductListResource;

class OrderItemResource extends JsonResource
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
        $productJson = json_decode($this->product_json);
        $bundleJson = json_decode($this->bundle_json);
        $variantJson = json_decode($this->variants_json);
        $countryJson = json_decode($this->country_json);
        $cityJson = json_decode($this->city_json);
        $productDetails = json_decode($this->product_details);
        $bundleDetails = json_decode($this->bundle_details);
        $new = [
            'id' => null,
            'name' => null,
            'description' => null,
            'nameFormat' =>  null,
            'descriptionFormat' => null,
            'sku' => null,
            'image' =>  null,
        ];
        if($this->product_id)
        {
            $new= [
                'id' => $productJson->id ?? null,
                'name' => $productJson->product_name ?? null,
                'description' => $productJson->product_description ?? null,
                'nameFormat' => $productJson->product_name_format ?? null,
                'descriptionFormat' => $productJson->product_description_format ?? null,
                'sku' => $productJson->sku ?? null,
                'image' => $productJson->image ?? null,
            ];
        }elseif($this->bundle_id)
            {
                $new= [
                    'id' => $bundleJson->id ?? null,
                    'name' => $bundleJson->bundle_name ?? null,
                    'description' => $bundleJson->bundle_description ?? null,
                    'nameFormat' => $bundleJson->bundle_name_format ?? null,
                    'descriptionFormat' => $bundleJson->bundle_description_format ?? null,
                    'sku' => $bundleJson->sku ?? null,
                    'image' => $bundleJson->image ?? null,
                ];
            }
        $product=null;
        $bundle=null;
        if($this->product_id)
        {
            $product = new ProductListResource($this->product);
        }
        if($this->bundle_id)
        {
            $bundle = new BundleListResource($this->bundle);
        }
        return [
            'id' => $this->id,
            'orderId' => $this->order_id,
            'product' => $this->product_id ? $product : ($this->bundle_id ? $bundle : null) ,
            'quantity' => $this->quantity,
            'unitPrice' => round($this->unitPrice, 2) ?? 0,
            'sellingPrice' => round($this->unitPrice, 2) ?? 0,
            'unitPriceText' => $this->unitPrice . currency() ?? 0,
            'totalPrice' => round($this->totalPrice, 2),
            'totalPriceText' => $this->totalPrice . currency() ?? 0,
            'variant_id' => $this->variant_id,
            'vat_profit' => $this->vat_profit,
            'net_profit' => $this->net_profit,
            'sku' => $this->sku,
            'isRefund' => ($this->is_refund === 1) ? true : false,
            'product_details' => $this->product_id ? $productDetails : ($this->bundle_id ? $bundleDetails : null),
            'isRelatedProducts' => $this->product ? $this->product->related_products->count() : 0,
            'product_new' => $new,
            'variants' => [
                'id' => $variantJson->id ?? null,
                'option' => $variantJson->option ?? null,
                'sku' => $variantJson->sku ?? null,
            ],

            'country' => [
                'id' => $countryJson->id ?? null,
                'name' => $countryJson->name ?? null,
            ],
            'city' => [
                'id' => $cityJson->id ?? null,
                'name' => $cityJson->name ?? null,
            ],
            'added_by' => $this->added_by,
            'discount' => $this->discount,
            'is_discount' => $this->is_discount,
        ];
    }
}
