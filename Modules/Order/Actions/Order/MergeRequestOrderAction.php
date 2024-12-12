<?php

namespace Modules\Order\Actions\Order;

use Modules\Order\Enums\OrderEnum;
use Exception;
use Modules\MasterCatalog\Entities\ProductVariantValue;
use Modules\MasterCatalog\Service\ProductService;
use Modules\Order\Actions\OrderItem\CreateOrderItemAction;
use Modules\Order\Enums\PaymentEnum;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\PlatformEnum;
use Modules\Order\Repositories\OrderRepository;
use Modules\MasterCatalog\Service\BundleService;

class MergeRequestOrderAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * The function `checkOrderAndCalculatorPrice` processes order items, calculates prices, checks
     * inventory, and ensures selling price is higher than cost price.
     *
     * @param array items The `checkOrderAndCalculatorPrice` function takes an array of items as input.
     * Each item in the array should have the following structure:
     * @param order The `checkOrderAndCalculatorPrice` function takes an array of items and an optional
     * order parameter.
     *
     * @return array An array containing the following keys:
     *               - 'totalPriceForProduct': array of total prices for each product
     *               - 'totalWeight': array of total weights for each product
     *               - 'totalProductVat': array of total VAT for each product
     *               - 'netProfit': array of net profits for each product
     *               - 'totalSellingPriceForProduct': array of total selling prices for each product
     */
    public function checkOrderAndCalculatorPrice(array $items, $order = null, $is_admin = false): array
    {
        $totalPriceForProduct = [];
        $totalSellingPriceForProduct = [];
        $totalWeight = [];
        $totalProductVat = [];
        $netProfit = [];

        foreach ($items as $item) {
            $itemId = $item['product'] ? $item['product'] : (isset($item['bundle']) ? $item['bundle'] : null);
            if ($item['product']) {
                $product = app()->make(ProductService::class)->show($item['product']);
                $productVariantValues = ProductVariantValue::find($item['variant'][0]);
                foreach ($item['variant'] as $variant) {
                    $checkVariant = ProductVariantValue::find((int) $variant);

                    if ($checkVariant) {

                        if ($checkVariant->product_variant_id != $productVariantValues->product_variant_id) {

                            return throw new Exception(trans('orders.value selected is currently out of stock, why don’t you try another!'));
                        }
                    }
                }

                if ($productVariantValues) {

                    $productPrice = $productVariantValues->productVariant->cost_price;
                    $productSellingPrice = $productVariantValues->productVariant->cost_price;
                    $weight = $productVariantValues->productVariant->weight;
                } else {

                    $productPrice = $item['sellingPrice'];
                    $productSellingPrice = $product['priceAfterDiscount'] ?? $product['cost_price'];
                    $weight = $product->weight;
                }

                $discount = $item['discount'] ?? 0;
                if ($discount > 0) {
                    $discountedPrice = $productPrice - ($productPrice * ($item['discount'] / 100));
                    $productPrice = round($discountedPrice, 2);
                }


                $totalPriceForProduct[] = ($productPrice * $item['quantity']);
                $totalWeight[] = ($weight * $item['quantity']);
                $totalProductVat[] = (App(CreateOrderItemAction::class)->getOrderItemVat($product, $item));
                $netProfit[] = App(CreateOrderItemAction::class)->netProfit($product, $item);
                $totalSellingPriceForProduct[] = ($productSellingPrice * $item['quantity']);
                if ($item['sellingPrice'] <= $product['cost_price'] && $is_admin == false) {

                    return throw new Exception(trans('orders.The Selling Price must be greater than the Cost Price.'));
                }
            } else {
                $bundle = app()->make(BundleService::class)->show($item['bundle']);
                $bundlePrice = $item['sellingPrice'];
                $bundleSellingPrice = $bundle->cost_price;
                $totalPriceForProduct[] = ($bundlePrice * $item['quantity']);
                $totalSellingPriceForProduct[] = ($bundleSellingPrice * $item['quantity']);
                $netProfit[] = ($bundleSellingPrice - $bundlePrice) * $item['quantity'];
                $totalWeight[] = 0;
                $totalProductVat[] = 0;
            }
        }

        return [
            'totalPriceForProduct' => $totalPriceForProduct,
            'totalWeight' => $totalWeight,
            'totalProductVat' => $totalProductVat,
            'netProfit' => $netProfit,
            'totalSellingPriceForProduct' => $totalSellingPriceForProduct,
        ];
    }

    /**
     * The function merges various request parameters into a single array and returns it.
     *
     * param request The request parameter is an instance of the Illuminate\Http\Request class, which
     * represents an HTTP request made to the application.
     * param shippingFees The shipping fees for the order.
     * param totalQuantity The total quantity of items in the order.
     * param totalPrice The total price of the order, including the cost of items and any additional
     * charges.
     * param countOrderItem The countOrderItem parameter represents the total number of items in the
     * order.
     * param costPrice The cost price of the items in the order.
     * param totalVat The totalVat parameter represents the total value-added tax (VAT) amount for the
     * order.
     * param weight The weight parameter represents the total weight of the order.
     *
     * @return the merged request object with additional key-value pairs.
     */
    public function mergeAllRequest($request, $shippingFees, $totalQuantity, $totalPrice, $countOrderItem, $costPrice, $totalVat, $netProfit, $weight, $order = null)
    {
        if ($order) {
            return $request->merge([
                'shippingFees' => $shippingFees,
                'shippingMethod' => 'shippingMethod',
                'totalQuantity' => $totalQuantity,
                'subTotal' => $totalPrice,
                'phone_code' => $request->phone_code ?? $order->phone_code,
                'grandTotal' => ($totalPrice + $shippingFees),
                'status_id' => $request->status_id ?? $order->status_id,
                'customerName' => $request->customerName ?? $order->customerName,
                'customerPhone' => $request->customerPhone ?? $order->customerPhone,
                'customerAddress' => $request->customerAddress ?? $order->customerAddress,
                'customerLocation' => $request->customerLocation ?? $order->customerLocation,
                'country_id' => $request->customerCountry ?? $order->country_id,
                'customerCity' => $request->customerCity ?? $order->customerCity,
                'countOrderItem' => $countOrderItem,
                'costPrice' => $costPrice,
                'totalVat' => $totalVat,
                'net_profit' => $netProfit,
                'weight' => $weight,
            ]);
        } else {
            if ($request->paymentMethod == PaymentEnum::ONLINE_METHOD_ID) {
                $statusId = OrderEnum::PAY_PENDING_STATUS;
            } elseif ($request->paymentMethod == PaymentEnum::WALLET_METHOD_ID) {
                $statusId = OrderEnum::PAY_PENDING_STATUS;
            } else {
                $statusId = OrderEnum::NEW_STATUS;
            }
            return $request->merge([
                'shippingFees' => $shippingFees,
                'shippingMethod' => 'shippingMethod',
                'totalQuantity' => $totalQuantity,
                'subTotal' => $totalPrice,
                'phone_code' => $request->phone_code,
                'grandTotal' => ($totalPrice + $shippingFees),
                'dropshipper_id' => ($request->dropshipper_id) ? $request->dropshipper_id : user()->id,
                'status_id' => $statusId,
                'customerName' => $request->customerName,
                'customerPhone' => $request->customerPhone,
                'customerAddress' => $request->customerAddress,
                'customerLocation' => $request->customerLocation,
                'country_id' => $request->customerCountry,
                'customerCity' => $request->customerCity,
                'countOrderItem' => $countOrderItem,
                'costPrice' => $costPrice,
                'totalVat' => $totalVat,
                'net_profit' => $netProfit,
                'weight' => $weight,
                'source_platform' => $request->source_platform ?? PlatformEnum::WEBSITE_PLATFORM,
                'created_platform' => $request->created_platform ?? PlatformEnum::WEBSITE_PLATFORM,
            ]);
        }
    }
}
