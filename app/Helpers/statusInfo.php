<?php

use Modules\Order\Enums\OrderEnum;
use Modules\CoreData\Entities\Status;
//todo change
//todo check all file
/**
 * The function retrieves the status of a given ID using the Status model in PHP.
 *
 * param id The parameter "id" is the identifier of the status that we want to retrieve. It is used to
 * query the database and find the corresponding status object.
 *
 * return the status of a record with the given ID from the "Status" table.
 */
function getStatusId($id)
{
    /*$getStatusId = Status::find($id);
     return $getStatusId->id;*/
    return $id;
}
function getStatusTitle($status_id)
{
    switch ($status_id) {
        case getStatusId(OrderEnum::NEW_STATUS):
            return trans('orders.New Order Created');
        case getStatusId(OrderEnum::PENDING_STATUS):
            return trans('orders.Order Pending');
        case getStatusId(OrderEnum::REJECTED_STATUS):
            return trans('orders.Order Status Rejected');
        case getStatusId(OrderEnum::SHIPPING_STATUS):
            return trans('orders.Order In Shipping');
        case getStatusId(OrderEnum::COMPLETED_STATUS):
            return trans('orders.Order Completed');
        case getStatusId(OrderEnum::CANCELED_STATUS):
            return trans('orders.Order Canceled');
        case getStatusId(OrderEnum::REFUND_BALANCE_STATUS):
            return trans('orders.Order Refund Balance');
        case getStatusId(OrderEnum::PAY_PENDING_STATUS):
            return trans('orders.Pending payment');
        case getStatusId(OrderEnum::PREPARING_STATUS):
            return trans('orders.prepare');
        case getStatusId(OrderEnum::READY_STATUS):
            return trans('orders.ready');
        case getStatusId(OrderEnum::PENDING_INVENTORY_STATUS):
            return trans('orders.pending_inventory');
        case getStatusId(OrderEnum::ONHOLD_STATUS):
            return trans('orders.onhold');
        default:
            return  trans('orders.Unknown Status');
    }
}
function getStatusDescription($status_id)
{
    switch ($status_id) {
        case getStatusId(OrderEnum::NEW_STATUS):
            return  trans('orders.This status is triggered when a new order has been created.');
        case getStatusId(OrderEnum::PENDING_STATUS):
            return trans('orders.This status is triggered when an order is pending and awaiting processing.');
        case getStatusId(OrderEnum::REJECTED_STATUS):
            return trans('orders.This status is triggered when an order has been rejected.');
        case getStatusId(OrderEnum::SHIPPING_STATUS):
            return trans('orders.This status is triggered when an order is in the shipping process.');
        case getStatusId(OrderEnum::COMPLETED_STATUS):
            return trans('orders.This status is triggered when an order has been successfully completed.');
        case getStatusId(OrderEnum::CANCELED_STATUS):
            return trans('orders.This status is triggered when an order has been canceled.');
        case getStatusId(OrderEnum::REFUND_BALANCE_STATUS):
            return trans('orders.This status is triggered when a Refund Balance has been requested for an order.');
        case getStatusId(OrderEnum::PAY_PENDING_STATUS):
            return trans('orders.Order is waiting for dropshipper to pay.');
        case getStatusId(OrderEnum::READY_STATUS):
            return trans('orders.READY_STATUS');
        case getStatusId(OrderEnum::PREPARING_STATUS):
            return trans('orders.preparing_desc');
        case getStatusId(OrderEnum::PENDING_INVENTORY_STATUS):
            return trans('orders.pending_inventory_desc');
        case getStatusId(OrderEnum::ONHOLD_STATUS):
            return trans('orders.onhold_desc');
        default:
            return trans('orders.Unknown Status');
    }
}
function getStatusColor($status_id)
{
    switch ($status_id) {
        case getStatusId(OrderEnum::NEW_STATUS):
            return '#009ef7'; // Light Salmon
        case getStatusId(OrderEnum::PENDING_STATUS):
            return '#FFD700'; // Gold
        case getStatusId(OrderEnum::REJECTED_STATUS):
            return '#FF0000'; // Red
        case getStatusId(OrderEnum::SHIPPING_STATUS):
            return '#1E90FF'; // Dodger Blue
        case getStatusId(OrderEnum::COMPLETED_STATUS):
        case getStatusId(OrderEnum::READY_STATUS):
            return '#32CD32'; // Lime Green
        case getStatusId(OrderEnum::CANCELED_STATUS):
            return '#A9A9A9'; // Dark Gray
        case getStatusId(OrderEnum::REFUND_BALANCE_STATUS):
            return '#FFA500'; // Orange
        default:
            return '#000000'; // Black (or any other default color you prefer)
    }
}
function getStatusText($name)
{
    switch ($name) {
        case 'new':
            return 'New';
        case 'pending':
            return 'Pending confirmation';
        case 'pending_inventory':
            return 'Pending inventory';
        case 'shipping':
            return 'Shipping';
        case 'rejected':
            return 'Rejected';
        case 'completed':
            return 'Completed';
        case 'canceled':
            return 'Canceled';
        case 'pay_pending':
        case 'payPending':
            return 'Pending payment';
        case 'preparing':
            return 'Preparing';
        case 'returned':
            return 'Returned';
        case 'onhold':
            return 'on hold';
        default:
            return $name;
    }
}
/**
 * The function `getStatusName` retrieves the name value of a status object based on its ID.
 *
 * param id The id parameter is the identifier of the status that you want to retrieve the name for.
 *
 * return the name value of the status with the given ID.
 */
function getStatusName($id)
{
    $getStatusId = Status::find($id);
    return trans('orders.' . $getStatusId->name->value);
}
function getStatusSupplierName($status_id)
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
            return 'Order is Pending inventory';
        case getStatusId(OrderEnum::ONHOLD_STATUS):
            return 'Order is ON HOLD';
        default:
            return 'Unknown Status';
    }
}

/**
 * The function returns a color code based on the input status.
 *
 * param status The input parameter for the function, which is a string representing the status of a
 * task or order.
 *
 * return a color code based on the input status.
 */
function status($status)
{
    if ($status === Status::COMPLETED_STATUS) {
        return '#00a038';
    } elseif ($status === Status::NEW_STATUS) {
        return '#9ba09d';
    } elseif ($status === Status::PENDING_STATUS) {
        return '#a40ed6';
    } elseif ($status === Status::REJECTED_STATUS || $status === Status::COMPLETED_STATUS) {
        return '#f20909';
    }
}
function setStatusClass($status)
{
    if ($status == 'new') {
        return 'badge-light-primary';
    }
    if ($status == 'pending') {
        return 'badge-light-secondary';
    }
    if ($status == 'rejected') {
        return 'badge-light-dark';
    }
    if ($status == 'delivered') {
        return 'badge-light-success';
    }
    if ($status == 'canceled') {
        return 'badge-light-danger';
    }
    if ($status == 'refundRequested') {
        return 'badge-light-primary';
    }
    if ($status == 'refundBalance') {
        return 'badge-light-primary';
    }
}
