<?php
// Copyright
declare(strict_types=1);

namespace Modules\Order\PaymentMethod;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Enums\PaymentEnum;
use Modules\Order\Entities\Order;
final class PaymentMethodList
{
    /**
     * It returns a list of payment methods.
     */

    public static function list()
    {
        $payment =  collect([]);

        $payment->push([
            'id' => 1,
            'code' => PaymentEnum::ONLINE_METHOD,
            'name' => trans('orders.Online payment'),
            'icon' => url('assets/payment/Online_Payment_0.png')
        ]);

        $payment->push([
            'id' => 2,
            'code' => PaymentEnum::CASH_ON_DELIVERY,
            'name' => trans('orders.Cash On Delivery payment'),

            'icon' => asset('assets/payment/cash.png')
        ]);
        
        $payment->push([
            'id' => 3,
            'code' => PaymentEnum::WALLET_METHOD,
            'name' => trans('orders.Wallet payment'),
            'icon' => asset('assets/payment/cash.png')
        ]);
        return $payment;
    }
}
