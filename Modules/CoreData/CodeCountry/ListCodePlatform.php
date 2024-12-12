<?php
// Copyright
declare(strict_types = 1);

namespace Modules\CoreData\CodeCountry;
//todo change
use Modules\Order\Entities\Order;

final class ListCodePlatform
{
    /**
     * It returns a List Code Country
     */
    public static function list()
    {
        $payment = collect([]);
        $payment->push(
            [
                'id' => 1,
                'name' => Order::WEBSITE_PLATFORM,
            ],
            [
                'id' => 2,
                'name' => Order::EASYORDER_PLATFORM,
            ],
            [
                'id' => 3,
                'name' => Order::TiKTOK_PLATFORM,
            ],
            [
                'id' => 4,
                'name' => Order::SALLA_PLATFORM,
            ],
        );
        return $payment;
    }
}
