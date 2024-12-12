<?php

namespace Modules\Order\Actions\PendingOrder;

use Illuminate\Http\Request;
use Modules\Order\Actions\Order\FakeNumberOrderAction;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderEnum;
use Modules\CoreData\Service\CityService;
use Modules\Basic\Traits\validationRulesTrait;
use Illuminate\Support\Facades\Auth;

class ValidationPendingOrdersAction
{
    use validationRulesTrait;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * The function `duplicatedOrders` checks for orders with the same customer phone number and
     * determines if any of them have the same items as the request items.
     *
     * @param customerPhone The `duplicatedOrders` function you provided seems to be checking for
     * duplicated orders based on the customer's phone number and the items in the order. If there are
     * orders with the same customer phone number and status (NEW, PAY PENDING, or PENDING), it then
     * compares the items in those
     * @param itemArray The `itemArray` parameter in the `duplicatedOrders` function likely represents
     * an array of items that are being checked for duplication in orders. This array could contain
     * information about the items being ordered, such as their IDs, quantities, or other relevant
     * details.
     *
     * @return The function `duplicatedOrders` returns an array with two keys:
     * 1. 'is_duplicated': A boolean value indicating whether there are duplicated orders based on the
     * provided customer phone number and item array.
     * 2. 'duplicated_order_ids': A JSON string containing the IDs of the duplicated orders if there
     * are any, or null if there are no duplicated orders.
     */
    public function duplicatedOrders($customerPhone, $itemArray, $dropshipper_id)
    {
        // Get orders with the same customer phone number
        $orders = Order::where('customerPhone', $customerPhone)->where('dropshipper_id', $dropshipper_id)->get();
        if ($orders->isEmpty()) {
            return [
                'is_duplicated' => false,
                'duplicated_order_ids' => null,
                'fresh_duplicated' => false
            ];
        }
        // Initialize arrays to store similar orders and their indicators
        $similarOrders = [];
        $isDuplicated = false;
        $freshDuplicate = false;
        // Check if any of those orders has the same items as the request items
        foreach ($orders as $order) {
            if ($this->areOrderItemsEqual($order, $itemArray)) {
                // Add the similar order to the collection
                array_push($similarOrders, $order->id);
                $isDuplicated = true;
                $freshDuplicate = $this->getFreshDuplicate($order);
            }
        }
        // Convert the array of order IDs to a JSON string
        $duplicatedOrderIdsJson = $similarOrders ? json_encode($similarOrders) : null;
        // Return the similar orders along with the indicator
        return [
            'is_duplicated' => $isDuplicated,
            'duplicated_order_ids' => $duplicatedOrderIdsJson,
            'fresh_duplicated' => $freshDuplicate
        ];
    }

    protected function getFreshDuplicate($order)
    {
        return $order->status_id == OrderEnum::NEW_STATUS || $order->status_id == OrderEnum::PAY_PENDING_STATUS || $order->status_id == OrderEnum::PENDING_STATUS;
    }

    /**
     * The function `areOrderItemsEqual` compares the items of an order with a given array of items to
     * check for equality.
     *
     * @param Order order The `areOrderItemsEqual` function compares the items of a given order with an
     * array of items. It first extracts the items of the order into an array format and then compares
     * it with the array of items passed as a parameter.
     * @param itemArray The `itemArray` parameter in the `areOrderItemsEqual` function represents an
     * array of items from a request. These items typically include details such as product ID,
     * quantity, and selling price. This function compares the items in the `itemArray` with the items
     * in the `Order` object
     *
     * @return The `areOrderItemsEqual` function is returning the result of calling the
     * `arrayValuesEqual` method with the `` and `` arrays as arguments. This
     * method is used to compare the values of the two arrays and determine if they are equal.
     */
    public function areOrderItemsEqual(Order $order, $itemArray)
    {
        // Get the items of the current order
        $orderItems = $order->orderItems->map(function ($item) {
            return [
                'product' => $item->product_id,
                'quantity' => $item->quantity,
                'sellingPrice' => $item->selling_price,
            ];
        })->toArray();
        // Get the items of the request
        $requestItems = $itemArray;
        // Check if the arrays are equal
        return $this->arrayValuesEqual($orderItems, $requestItems);
    }

    /**
     * The function `arrayValuesEqual` compares the "product" values extracted from two arrays after
     * sorting them to determine if they are equal.
     *
     * @param arr1 arr1 is an array containing elements with a 'product' key. The function extracts the
     * 'product' values from each element in arr1, converts them to strings, sorts the resulting array,
     * and then compares it with a similar process applied to arr2. The function ultimately returns a
     * boolean value indicating
     * @param arr2 arr2 is an array containing elements with a 'product' key. The array is used in the
     * arrayValuesEqual function to compare the 'product' values with another array (arr1) to determine
     * if the values are equal after sorting and converting to strings.
     *
     * @return The function `arrayValuesEqual` is returning a boolean value indicating whether the
     * "product" values extracted from two arrays `` and `` are equal after sorting and
     * comparison.
     */
    public function arrayValuesEqual($arr1, $arr2)
    {
        // Extract the "product" values from each array
        $products1 = array_map('strval', array_column($arr1, 'product'));
        $products2 = array_map('strval', array_column($arr2, 'product'));
        // Sort the arrays to ensure consistent comparison
        sort($products1);
        sort($products2);
        // Check if the sorted arrays are equal
        return $products1 === $products2;
    }

    public function hasEmptyFields($row)
    {
        if (empty($row) || $row['customer_name'] === null || $row['customer_phone'] === null || $row['customer_address'] === null || $row['district'] === null || $row['customer_city'] === null || $row['country_id'] === null || $row['payment_method'] === null) {
            return true;
        }
        return false;
    }

    public function hasInvalidCustomerName($senderName)
    {
        if (!preg_match("~^[a-z0-9٠-٩\-+,()/'\s\p{Arabic}]{1,60}$~iu", $senderName)) {
            return true;
        }
        return false;
    }

    public function hasInvalidCountry($row)
    {
        if (!in_array($row, ['KSA', 'UAE', 'EGY'])) {
            return true;
        }
        return false;
    }

    public function hasInvalidCity($record)
    {
        $city = app()->make(CityService::class)->search(new Request(['alias' => array_merge(
            [$record],
            array_filter(explode(' ', $record))
        )]));
        if (empty($city)) {
            return true;
        }
        return false;
    }

    public function allValidation($row, $checkPayment = true)
    {
        $message = [];
        $invalid = false;
        $invalidPhone = false;
        $hasEmptyFields = $this->hasEmptyFields($row);
        if ($hasEmptyFields) {
            $message[] = trans('orders.Found Empty column');
            $invalid = true;
        }
        $row['customer_phone'] = $this->handlePhoneKSA($row['customer_phone']);
        $phoneNumber = $this->hasInvalidPhoneKSA($row['customer_phone']);
        if (!$phoneNumber) {
            $message[] = trans('orders.The phone field must be 10 number or the first start 05.');
            $invalid = true;
            $invalidPhone = true;
        }
        $checkFake = $this->CheckFakeOrder($row['customer_phone']);
        if ($checkFake) {
            $message[] = trans('orders.fake');
            $invalid = true;
        }
        $name = $this->hasInvalidCustomerName($row['customer_name']);
        if ($name) {
            $message[] = trans('orders.Un valid customer name');
            $invalid = true;
        }
        if ($row['payment_method'] == 1 && !user()->DropshipperOptionCheck('accept_payment_online_bulk') && (int)!setting('ONLINE_METHOD') ) {
            $message[] = trans('orders.Payment method is not supported by DropShipper');
            $invalid = true;
        }

        if ($row['payment_method'] == 2 &&  (int)!setting('CASH_ON_DELIVERY')) {
            $message[] = trans('orders.Payment method is not supported by DropShipper');
            $invalid = true;
        }

        if ($row['payment_method'] == 3 &&  (int)!setting('WALLET_METHOD')) {
            $message[] = trans('orders.Payment method is not supported by DropShipper');
            $invalid = true;
        }
        $city = $this->hasInvalidCity($row['customer_city']);
        if ($city) {
            $message[] = trans('orders.City Not Found');
            $invalid = true;
        }
        if (isset($row['city_in_country'])) {
            if ($row['city_in_country'] == false) {
                $message[] = trans('orders.City Not Found in Country');
                $invalid = true;
            }
        }
        $country = $this->hasInvalidCountry($row['customer_country']);
        if ($country) {
            $message[] = trans('orders.Wrong Country');
            $invalid = true;
        }
        $validationProduct = $this->validationProduct($row['items']);
        if ($validationProduct['invalid']) {
            $message[] = $validationProduct['message'];
            $invalid = true;
        }
        if (!$invalidPhone) {
            $check = $this->duplicatedOrders(
                $row['customer_phone'],
                $row['items'],
                $row['dropshipper_id'] ?? user()->id
            );
            $row['is_duplicated'] = $check['is_duplicated'];
            if ($check['fresh_duplicated']) {
                $message[] = trans('orders.Order is duplicated.');
                $invalid = true;
            }
            $row['duplicated_order_ids'] = $check['duplicated_order_ids'];
            if ($check['is_duplicated'] && !$check['fresh_duplicated']) {
                $message[] = trans('orders.The request was made before but it will be useful to format it normally');
                $row['duplicated_order_ids'] = $check['duplicated_order_ids'];
            }
        }
        $message = json_encode([implode(',', array_unique($message))]);
        $row['message'] = $message ?? null;
        $row['invalid'] = $invalid;
        return $row;
    }

    public function validationProduct($itemArray)
    {
        $invalid = false;
        $message = [];
        foreach ($itemArray as $item) {
            if (!$item['product']) {
                $invalid = true;
                $message[] = trans('orders.Product SKU not found') . $item['sku'];
            }
            if ($item['product']) {
                if ($item['quantity'] <= 0) {
                    $invalid = true;
                    $message[] = trans('orders.Product Quantity Wrong');
                }
                if ($item['selling_price'] <= $item['productData']->cost_price) {
                    $invalid = true;
                    $message[] = trans('orders.The Selling Price must be greater than the Cost Price.');
                }
                if ($item['selling_price'] == null) {
                    $invalid = true;
                    $message[] = trans('orders.Selling price missing');
                }
            }
        }
        return ['invalid' => $invalid, 'message' => implode(',', $message)];
    }

    public function CheckFakeOrder($phone)
    {
        return app(FakeNumberOrderAction::class)->execute($phone);
    }
}
