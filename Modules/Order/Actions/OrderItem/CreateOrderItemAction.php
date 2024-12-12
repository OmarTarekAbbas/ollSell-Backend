<?php

namespace Modules\Order\Actions\OrderItem;

use Modules\CoreData\Entities\City;
use Modules\CoreData\Entities\Country;
use Modules\Order\Entities\OrderItem;
use Modules\MasterCatalog\Entities\Attribute;
use Modules\MasterCatalog\Service\ProductService;
use Modules\MasterCatalog\Entities\AttributeOption;
use Modules\Order\Repositories\OrderItemRepository;
use Modules\MasterCatalog\Entities\ProductVariantValue;
use Modules\Order\Entities\Order;
use Modules\MasterCatalog\Service\BundleService;

class CreateOrderItemAction
{
    protected $order;
    protected $import = false;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * This function executes an order by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the order data.
     *
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     *
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute()
    {
        if (request()->url() == url('api/order/import')) {
            $this->import = true;
        }

        if ($this->order->id) {
            $orderItems = OrderItem::where('order_id', $this->order->id)->get();

            foreach ($orderItems as $orderItem) {
                $orderItem->delete();
            }
        }

        $request = request();

        $items = $request->items;

        foreach ($items as $item) {
            if (!isset($item['sellingPrice']) && isset($item['unitPrice'])) {
                $item['sellingPrice'] = $item['unitPrice'];
            }

            $productVariantValues = null;
            if (isset($item['bundle'])) {
                $bundle = app()->make(BundleService::class)->showBundle($item['bundle']);
            } else {
                $product = app()->make(ProductService::class)->showProduct(isset($item['product_id']) ? $item['product_id'] : $item['product']);
                $productVariantValues = ProductVariantValue::find($item['variant'][0]);
            }

            $sku = null;
            $unitPrice = null;
            if ($productVariantValues) {
                $unitPrice = $productVariantValues->productVariant->price;
                $sku = $productVariantValues->productVariant->sku;
            } else {
                // TODO check unit price
                if (isset($item['bundle'])) {
                    $unitPrice = $this->getProductUnitPrice($bundle, $item);
                    $sku = $bundle->sku;
                } else {
                    $unitPrice = $this->getProductUnitPrice($product, $item);
                    $sku = $product->sku;
                }
            }

            $option = [];

            foreach ($item['variant'] as $variant) {
                $productVariantValue = ProductVariantValue::find((int)$variant);

                if ($productVariantValue) {
                    $attributeOption = AttributeOption::find((int)$productVariantValue->attribute_option_id);
                    $attribute = Attribute::find($attributeOption->attribute_id);
                    $option[$attribute->name] = $attributeOption->name;
                }
            }
            $country = Country::find($this->order->country_id);
            $city = City::find($this->order->customerCity);
            $countryName = collect($country->getTotalTranslation)->pluck('value')->toArray();
            $cityName = collect($city->getTotalTranslation)->pluck('value')->toArray();

            if (isset($product)) {
                $productName = collect($product->getTotalTranslation)->pluck('value')->toArray();
                $productDescription = collect($product->getTotalTranslationDescription)->pluck('value')->toArray();
            }

            if (isset($bundle)) {
                $bundleName = collect($bundle->getTotalTranslation)->pluck('value')->toArray();
                $bundleDescription = collect($bundle->getTotalTranslationDescription)->pluck('value')->toArray();
            }

            $isDiscount = 0;
            $discount = $item['discount'] ?? 0;
            if ($discount > 0) {
                $isDiscount = 1;
            }

            //Log::info("item",$item);
            if (isset($item['bundle'])) {
                $request->merge([
                    'order_id' => $this->order->id,
                    'bundle_id' => $bundle->id,
                    'unitPrice' => $unitPrice ?? $bundle->cost_price,
                    'totalPrice' => ($unitPrice * $item['quantity']),

                    'total_profit' =>  $this->totalProfit($bundle, $item),
                    'vat_profit' => $this->vatProfit($bundle, $item),
                    'net_profit' => $this->netProfit($bundle, $item),
                    'product_vat' => $this->productVat($bundle, $item),

                    'quantity' => $item['quantity'],
                    'added_by' => isset($item['added_by']) ? $item['added_by'] : null,
                    'sku' => $sku ?? $bundle->sku,
                    'supplier_id' => $bundle->supplier_id,
                    'status_id' => $this->order->status_id,
                    'bundle_json' => json_encode([
                        'id' => $bundle->id,
                        'name' => $bundleName,
                        'description' => $bundleDescription,
                        'image' => isset($bundle->logo[0]) ?
                            getFile($bundle->logo[0]['file'], pathType()['ip'], getFileNameServer($bundle->logo[0])) :
                            null,
                    ]),
                    'bundle_details' => json_encode([
                        $bundle
                    ]),
                    'product_id' => null,
                ]);
            } else {
                $request->merge([
                    'order_id' => $this->order->id,
                    'product_id' => isset($item['product_id']) ? $item['product_id'] : $item['product'],
                    'unitPrice' => $unitPrice ?? $product->price,
                    'totalPrice' => ($unitPrice * $item['quantity']),
                    'total_profit' =>  $this->totalProfit($product, $item),
                    'vat_profit' => $this->vatProfit($product, $item),
                    'net_profit' => $this->netProfit($product, $item),
                    'product_vat' => $this->productVat($product, $item),
                    'quantity' => $item['quantity'],
                    'added_by' => isset($item['added_by']) ? $item['added_by'] : null,
                    // 'vat' => $this->getOrderItemVat($product, $item),
                    'vat' => $this->vatProfit($product, $item),
                    'sku' => $sku ?? $product->sku,
                    'supplier_id' => $product->supplier_id,
                    'status_id' => $this->order->status_id,
                    'variant_id' => $productVariantValues->productVariant->id ?? 0,
                    'discount' => $item['discount'] ?? 0,
                    'is_discount' => $isDiscount,
                    'product_details' => json_encode([
                        $product
                    ]),
                    'country_json' => json_encode([
                        'id' => $country->id,
                        'name' => ['en' => $countryName[0], 'ar' => $countryName[1]],
                    ]),
                    'city_json' => json_encode([
                        'id' => $city->id,
                        'name' => ['en' => $cityName[0], 'ar' => $cityName[1]],
                    ]),
                    'product_json' => json_encode([
                        'id' => $product->id,
                        'sku' => $product->sku,
                        'product_name' => $productName[1],
                        'product_description' => $productDescription[1],
                        'product_name_format' => ['en' => $productName[0], 'ar' => $productName[1]],
                        'product_description_format' => ['en' => $productDescription[0], 'ar' => $productDescription[1]],
                        'image' => isset($product->logo[0]) ?
                            getFile($product->logo[0]['file'], pathType()['ip'], getFileNameServer($product->logo[0])) :
                            null,
                    ]),
                    'variants_json' => ($productVariantValues) ? json_encode(['id' => $productVariantValues->productVariant->id, 'sku' => $productVariantValues->productVariant->sku, 'option' => $option]) : null,
                ]);
            }
            //Log::info("request", $request->all());
            $data = App(OrderItemRepository::class)->save($request);
        }

        if ($data) {
            return true;
        }

        return false;
    }

    public function getProductUnitPrice($product, $item)
    {
        // if ($this->import) {
        //     return $item['unitPrice'];
        // }
        // return $item['sellingPrice'];


        if ($this->import) {
            $productPrice = $item['unitPrice'];
        }
        $productPrice = $item['sellingPrice'];

        $discount = $item['discount'] ?? 0;
        if ($discount > 0) {
            $discountedPrice = $productPrice - ($productPrice * ($discount / 100));
            $productPrice = round($discountedPrice, 2);
        }
        return $productPrice;
    }

    public function getOrderItemVat($product, $item)
    {
        if ($this->import) {
            $itemSellingPrice = $item['unitPrice'];
        } else {
            $itemSellingPrice = $item['sellingPrice'];
        }


        $productBasePrice = $product->is_discount ? $product->priceAfterDiscount : $product->cost_price;
        $baseVat = $productBasePrice * (float)setting('vat_product') ?? 0.15;
        $totalProfit = $itemSellingPrice - $productBasePrice;
        $profitVat = $totalProfit * (float)setting('vat_product') ?? 0.15;
        $netProfit = $totalProfit - $profitVat;
        $totalVat = $baseVat + $profitVat;

        // return ($totalVat * $item['quantity']);
        return ($totalVat);
    }

    public function totalProfit($product, $item)
    {
        return ($item['sellingPrice'] - $product->cost_price) * $item['quantity'];
    }

    public function vatProfit($product, $item)
    {
        $totalProfit =  $item['sellingPrice'] - $product->cost_price;
        return ($totalProfit * (float)setting('vat_profit') ?? 0.15) * $item['quantity'];
    }

    public function netProfit($product, $item)
    {
        $netProfit = $this->totalProfit($product, $item) - $this->vatProfit($product, $item);
        return $netProfit;
    }

    public function productVat($product, $item)
    {
        return ($product->cost_price *  (float)setting('vat_product') ?? 0.15) * $item['quantity'];
    }
}
