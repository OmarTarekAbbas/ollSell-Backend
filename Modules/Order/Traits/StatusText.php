<?php

namespace Modules\Order\Traits;

use Modules\Order\Enums\OrderEnum;
trait StatusText
{
    /**
     * It returns the string "Create New Order" if the status is equal to the constant NEW_STATUS.
     *
     * return The statusText() method returns the string 'Create New Order' if the status property is
     * equal to the constant NEW_STATUS.
     */
    public function statusTitle($status_id)
    {
        switch ($status_id) {
            case getStatusId(OrderEnum::NEW_STATUS):
                return 'New';
            case getStatusId(OrderEnum::PENDING_STATUS):
                return 'Pending Confirmation';
            case getStatusId(OrderEnum::REJECTED_STATUS):
                return 'Rejected';
            case getStatusId(OrderEnum::SHIPPING_STATUS):
                return 'Shipping';
            case getStatusId(OrderEnum::COMPLETED_STATUS):
                return 'Delivered';
            case getStatusId(OrderEnum::CANCELED_STATUS):
                return 'Cancelled';
            case getStatusId(OrderEnum::PAY_PENDING_STATUS):
                return 'Pending Payment';
            case getStatusId(OrderEnum::PREPARING_STATUS):
                return 'Preparing';
            case getStatusId(OrderEnum::READY_STATUS):
                return 'Order is ready';
            case getStatusId(OrderEnum::PENDING_INVENTORY_STATUS):
                return trans('orders.pending_inventory');
            default:
                return 'Unknown Status';
        }
    }

    /**
     * The function returns a description based on the status of an order.
     *
     * return a description of the status of an order based on the value of the status property.
     */
    public function statusDescription($status_id)
    {
        switch ($status_id) {
            case getStatusId(OrderEnum::NEW_STATUS):
                return 'A new order has been created and is awaiting processing.';
            case getStatusId(OrderEnum::PENDING_STATUS):
                return 'The order is pending and awaiting processing.';
            case getStatusId(OrderEnum::REJECTED_STATUS):
                return 'The order has been rejected.';
            case getStatusId(OrderEnum::SHIPPING_STATUS):
                return 'The order is currently in the shipping process.';
            case getStatusId(OrderEnum::COMPLETED_STATUS):
                return 'The order has been successfully completed.';
            case getStatusId(OrderEnum::CANCELED_STATUS):
                return 'The order has been canceled.';
            case getStatusId(OrderEnum::PAY_PENDING_STATUS):
                return 'The order is pending for dropshipper payment.';
            case getStatusId(OrderEnum::PREPARING_STATUS):
                return 'The order is being reviewed and prepared for shipping.';
            case getStatusId(OrderEnum::READY_STATUS):
                return 'The order is ready.';
            case getStatusId(OrderEnum::PENDING_INVENTORY_STATUS):
                return trans('orders.pending_inventory_desc');
            default:
                return 'Unknown Status';
        }
    }

    /**
     * The function returns a text description based on the status of an order.
     *
     * return a string that describes the status of the order based on the value of the
     * variable.
     */
    public function statusColor($status_id)
    {
        switch ($status_id) {
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
