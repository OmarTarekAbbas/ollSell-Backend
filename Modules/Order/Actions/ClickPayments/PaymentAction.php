<?php

namespace Modules\Order\Actions\ClickPayments;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\App;
use Modules\Order\Enums\ClickPayEnum;
use Modules\Order\Enums\PaymentEnum;
use Modules\Order\Integration\Payments\Methods\ClickPayments;

class PaymentAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct() {}

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
    public function execute($id)
    {
        $order = Order::find($id);
        $clickPay = App::make(ClickPayments::class);
        $order->status_click_payment= ClickPayEnum::Open;
        $order->checkOutId = $clickPay->initiate($order, $order->grandTotal, 'Visa');
        $order->save();
        $order->refresh();
        $checkOutIdData = json_decode($order->checkOutId, true); // true to decode as an associative array
        if(isset($checkOutIdData['code']) && $checkOutIdData['code'] == 4)
        {
            $order = Order::find($id);
            $order->status_click_payment= ClickPayEnum::Duplicate;
            $order->save();
            $order->refresh();
        }
        if (isset($checkOutIdData['redirect_url'])) {
            return $checkOutIdData['redirect_url'];
        } else {
            return null;
        }
    }
}
