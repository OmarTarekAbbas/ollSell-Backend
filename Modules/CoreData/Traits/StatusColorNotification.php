<?php

namespace Modules\CoreData\Traits;

use Modules\Order\Enums\OrderEnum;

use Modules\Order\Entities\Order;

class StatusColorNotification
{
    
    /**
     * The function `statusColor` takes a status parameter and returns a corresponding color code based
     * on the status.
     * 
     * param status The parameter "status" is the status of an order. It is used to determine the
     * color associated with that status.
     * 
     * @return string a string representing the color code associated with the given status.
     */
    public static function statusColor($status)
    {
        switch ($status) {
            case getStatusId(OrderEnum::NEW_STATUS):
                return '#FFA07A'; // Light Salmon
            case getStatusId(OrderEnum::PENDING_STATUS):
                return '#FFD700'; // Gold
            case getStatusId(OrderEnum::REJECTED_STATUS):
                return '#FF0000'; // Red
            case getStatusId(OrderEnum::SHIPPING_STATUS):
                return '#1E90FF'; // Dodger Blue
            case getStatusId(OrderEnum::COMPLETED_STATUS):
                return '#32CD32'; // Lime Green
            case getStatusId(OrderEnum::CANCELED_STATUS):
                return '#A9A9A9'; // Dark Gray
            case getStatusId(OrderEnum::PAY_PENDING_STATUS):
                return '#FFA500'; // Orange
            default:
                return '#000000'; // Black (or any other default color you prefer)
        }
    }
}
