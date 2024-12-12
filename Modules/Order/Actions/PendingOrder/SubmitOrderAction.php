<?php

namespace Modules\Order\Actions\PendingOrder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Finance\Actions\Transaction\PayByWalleteAutomaticAction;
use Modules\Order\Actions\Order\FakeNumberOrderAction;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\DB;
use Modules\Order\Enums\OrderEnum;
use Illuminate\Support\Facades\Log;
use Modules\CoreData\Entities\Country;
use Modules\Order\Enums\PlatformEnum;
use Modules\Order\Service\OrderService;
use Modules\Order\Entities\PendingOrder;
use Modules\CoreData\Service\CityService;
use Modules\MasterCatalog\Entities\Product;
use Modules\Order\Entities\PendingOrderItem;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\Order\Actions\Order\CheckSubmitDuplicatedOrderAction;
use Modules\Order\Enums\PaymentEnum;

class SubmitOrderAction
{
    use validationRulesTrait;

    protected $pendingOrders;

    public function __construct($pendingOrders)
    {
        $this->pendingOrders = $pendingOrders;
    }

    public function execute()
    {
        foreach ($this->pendingOrders as $row) {
            try {
                $pendingOrder = PendingOrder::find($row->id);
                if ($pendingOrder->invalid) continue;
                $request = request();
                $request->merge([
                    'is_duplicated' => false,
                    'duplicated_order_ids' => null,
                    'fresh_duplicated' => false
                ]);
                $message = [];
                $correct = false;
                if ($this->hasEmptyFields($row)) {
                    $message[] = trans('orders.Found Empty column');
                    $correct = true;
                }
                if ($this->hasInvalidPhoneNumber($row)) {
                    $message[] = trans('orders.The phone field must be 10 number or the first start 05.');
                    $correct = true;
                }
                $checkFake = $this->CheckFakeOrder($row->customer_phone);
                if ($checkFake) {
                    $message[] = trans('orders.fake');
                    $correct = true;
                }
                if ($this->hasInvalidCustomerName($row->customer_name)) {
                    $message[] = trans('orders.Un valid customer name');
                    $correct = true;
                }
                if ($this->hasInvalidCountry($row)) {
                    $message[] = trans('orders.Wrong Country');
                    $correct = true;
                }
                $itemArray = [];
                $items = $row->pendingOrderItems;
                $totalPriceForProduct = [];
                $totalSellingPriceForProduct = [];
                $itemImport = [];
                foreach ($items as $item) {
                    $itemImport = $item;
                    $product = Product::where('sku', $item->sku)->first();
                    if ($product) {
                        $can = true;
                        $ids = $product->product_dropshippers->pluck('dropshipper_id');
                        if ($ids->count()) {
                            if (!in_array(user()->id, $ids->toArray())) {
                                $can = false;
                            }
                        }
                        if ($product->isApproved && $product->status && $can) {
                            if (!$product) {
                                $correct = true;
                                $message[] = trans('orders.Product SKU not found' . $item['sku']);
                            }
                            if ($item->selling_price <= $product['cost_price']) {
                                $correct = true;
                                $message[] = trans('orders.The Selling Price must be greater than the Cost Price.');
                            }
                            $totalPriceForProduct[] = ($item->quantity * $item->selling_price);
                            $totalSellingPriceForProduct[] = ($product['cost_price'] * $item->quantity);
                            $itemArray[] = [
                                'product' => $product['id'],
                                'quantity' => $item->quantity,
                                'unitPrice' => $item->selling_price,
                                'variant' => [
                                    0 => 'null',
                                ],
                            ];
                        } else {
                            $correct = true;
                            $message[] = trans('orders.Product SKU not found' . $item['sku']);
                        }
                    } else {
                        $correct = true;
                        $message[] = trans('orders.Product SKU not found' . $item['sku']);
                    }
                }
                $totalQuantity = collect($itemArray)->sum('quantity');
                $countOrderItem = collect($items)->count();
                $totalPrice = collect($totalPriceForProduct)->sum();
                $costPrice = collect($totalSellingPriceForProduct)->sum();
                $shippingFees = setting('shipping_fee') ?? 25;
                if ($row->customer_country == 'KSA') {
                    $request->merge(['code' => 'sa']);
                    $code = 'sa';
                }
                if ($row->customer_country == 'UAE') {
                    $code = 'ae';
                }
                if ($row->customer_country == 'EGY') {
                    $code = 'eg';
                }
                $country = Country::where('code', $code)->first();
                $countryId = $country['id'];
                $city = app()->make(CityService::class)->search(new Request(['id' => $row->city_id]));
                if (!$city || $city->country_id !== $countryId) {
                    $message[] = trans('orders.City Not Found in Country');
                }
                $cityId = $city->id;
                $paymentMethod = $row->payment_method;
                if ($paymentMethod == 1 && user()->DropshipperOptionCheck('convert_payment_online_to_wallet')) {
                    $paymentMethod = 3;
                }
                if ($paymentMethod == 1 && !user()->DropshipperOptionCheck('accept_payment_online_bulk')  && (int)!setting('ONLINE_METHOD')) {
                    $message[] = trans('orders.Payment method is not supported by DropShipper');
                    $correct = true;
                }

                if ($row['payment_method'] == 2 &&  (int)!setting('CASH_ON_DELIVERY')) {
                    $message[] = trans('orders.Payment method is not supported by DropShipper');
                    $correct = true;
                }

                if ($paymentMethod == 3 &&  (int)!setting('WALLET_METHOD')) {
                    $message[] = trans('orders.Payment method is not supported by DropShipper');
                    $correct = true;
                }

                if ($paymentMethod == PaymentEnum::ONLINE_METHOD_ID) {
                    $statusId = OrderEnum::PAY_PENDING_STATUS;
                } elseif ($paymentMethod == PaymentEnum::WALLET_METHOD_ID) {
                    $statusId = OrderEnum::PAY_PENDING_STATUS;
                } else {
                    $statusId = OrderEnum::NEW_STATUS;
                }
                $totalProductVat = $this->getOrderItemVatImport($product, $itemImport);
                $netProfit = $this->netProfitImport($product, $itemImport);
                $request->merge([
                    'shippingFees' => $shippingFees,
                    'shippingMethod' => 'shippingMethod',
                    'totalQuantity' => $totalQuantity,
                    'subTotal' => $totalPrice,
                    'grandTotal' => ($totalPrice + $shippingFees),
                    'dropshipper_id' => $row->dropshipper_id,
                    'status_id' => $statusId,
                    'customerName' => $row->customer_name,
                    'customerPhone' => $row->customer_phone,
                    'customerAddress' => $row->customer_address,
                    'source_platform' => $row->source_platform ?? PlatformEnum::WEBSITE_PLATFORM,
                    'created_platform' => PlatformEnum::WEBSITE_PLATFORM,
                    'district' => $row->district,
                    'country_id' => $countryId,
                    'customerCity' => $cityId,
                    'countOrderItem' => $countOrderItem,
                    'paymentMethod' => $paymentMethod,
                    'items' => $itemArray,
                    'costPrice' => $costPrice,
                    'phone_code' => 966,
                    'net_profit' => $netProfit,
                    'totalVat' => $totalProductVat,
                    'is_import' => 1,
                    'weight' => 0,
                ]);
                $duplicatedOrders = (new CheckSubmitDuplicatedOrderAction(
                    request: $request
                ))->execute();
                $request->merge([...$duplicatedOrders]);
                if ($request->fresh_duplicated) {
                    $message[] = trans('orders.Order is duplicated.');
                    $correct = true;
                }
                if ($correct == false) {
                    $data = app()->make(OrderService::class)->storeImport($request);
                    if ($data->is_duplicated) {
                        // Retrieve the IDs of duplicated orders
                        $duplicatedOrderIds = json_decode($data->duplicated_order_ids, true);
                        // Update duplicated orders
                        foreach ($duplicatedOrderIds as $orderId) {
                            // Retrieve the duplicated order
                            $duplicatedOrder = Order::find($orderId);
                            $duplicatedOrderDuplicatedIds = json_decode($duplicatedOrder->duplicated_order_ids, true);
                            // Add the ID of the current order to the duplicated order's list of duplicated_order_ids
                            $duplicatedOrderDuplicatedIds[] = $data->id;
                            // Encode the array back to JSON and assign it to the property
                            $duplicatedOrder->duplicated_order_ids = json_encode($duplicatedOrderDuplicatedIds);
                            $duplicatedOrder->is_duplicated = true;
                            // Save the changes
                            $duplicatedOrder->save();
                        }
                    }
                }
                if ($correct) {
                    $message = json_encode([implode(',', $message)]);
                    DB::table('pending_orders')
                        ->where('id', $row->id)
                        ->update(['message' => $message, 'invalid' => $correct]);
                    continue;
                }
                if ($data) {
                    if (user()->DropshipperOptionCheck('automatic_pay_from_profit_at_wallet') && $data->paymentMethod == PaymentEnum::WALLET_METHOD_ID) {
                        $orderFind = Order::find($data->id);
                        $check = app(PayByWalleteAutomaticAction::class)->execute($orderFind);
                        if ($check) {
                            app()->make(OrderService::class)->payWallet($orderFind);
                        }
                    }
                    PendingOrder::where('id', $row->id)->delete();
                    PendingOrderItem::where('pending_order_id', $row->id)->delete();
                }
            } catch (\Exception $e) {
                Log::info($e->getMessage());
                continue;
            }
        }
        return true;
    }

    private function hasEmptyFields($row): bool
    {
        return empty($row);
    }

    private function hasInvalidPhoneNumber($row): bool
    {
        $phoneNumber = $this->handlePhoneKSA($row->customer_phone);
        return !$this->hasInvalidPhoneKSA($phoneNumber);
    }

    public function hasInvalidCustomerName($senderName)
    {
        if (!preg_match("~^[a-z0-9٠-٩\-+,()/'\s\p{Arabic}]{1,60}$~iu", $senderName)) {
            return true;
        }
        return false;
    }

    private function hasInvalidCountry($row): bool
    {
        return !in_array($row->customer_country, ['KSA', 'UAE', 'EGY']);
    }

    function containsOnlyNull($input): bool
    {
        return empty(array_filter($input, function ($a) {
            return $a !== null;
        }));
    }

    public function getOrderItemVatImport($product, $row)
    {
        $itemSellingPrice = $row->selling_price;
        $productBasePrice = $product->is_discount ? $product->priceAfterDiscount : $product->cost_price;
        $baseVat = $productBasePrice * (float)setting('vat_product') ?? 0.15;
        $totalProfit = $itemSellingPrice - $productBasePrice;
        $profitVat = $totalProfit * (float)setting('vat_product') ?? 0.15;
        $netProfit = $totalProfit - $profitVat;
        $totalVat = $baseVat + $profitVat;
        return ($totalVat * $row->quantity);
    }

    public function netProfitImport($product, $itemImport)
    {
        $netProfit = $this->totalProfitImport($product, $itemImport) - $this->vatProfitImport($product, $itemImport);
        return $netProfit;
    }

    public function totalProfitImport($product, $itemImport)
    {
        return ($itemImport->selling_price - $product->cost_price) * $itemImport->quantity;
    }

    public function vatProfitImport($product, $itemImport)
    {
        $totalProfit = $itemImport->selling_price - $product->cost_price;
        return ($totalProfit * (float)setting('vat_profit') ?? 0.15) * $itemImport->quantity;
    }

    public function CheckFakeOrder($phone)
    {
        return app(FakeNumberOrderAction::class)->execute($phone);
    }
}
