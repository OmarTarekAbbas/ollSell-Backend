<?php

namespace Modules\Order\Enums;

enum PaymentEnum: int
{
    /**
     * Payment methods list
     *
     * @case string
     */
    const CASH_ON_DELIVERY = 'cashOnDelivery';

    const ONLINE_METHOD = 'online';

    const WALLET_METHOD = 'wallet';

    /**
     * Payment methods list
     *
     * @case int
     */
    const ONLINE_METHOD_ID = 1;

    const CASH_ON_DELIVERY_ID = 2;

    const WALLET_METHOD_ID = 3;
}
