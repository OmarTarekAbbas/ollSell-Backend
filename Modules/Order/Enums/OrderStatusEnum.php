<?php

namespace Modules\Order\Enums;

use Modules\CoreData\Entities\Status;

enum OrderStatusEnum: int
{
    //todo change
    const  NEW_STATUS = Status::NEW_STATUS;
    const  PAY_PENDING_STATUS = Status::PAY_PENDING_STATUS;
    const  PENDING_STATUS = Status::PENDING_STATUS;
    const  PENDING_INVENTORY_STATUS = Status::PENDING_INVENTORY_STATUS;
    const  PREPARING_STATUS = Status::PREPARING_STATUS;
    const  SHIPPING_STATUS = Status::SHIPPING_STATUS;
    const  REJECTED_STATUS = Status::REJECTED_STATUS;
    const  COMPLETED_STATUS = Status::COMPLETED_STATUS;
    const  CANCELED_STATUS = Status::CANCELED_STATUS;
    const  REFUND_REPLACEMENT_REQUESTED_STATUS = Status::REFUND_REPLACEMENT_REQUESTED_STATUS;
    const  REFUND_REPLACEMENT_REQUESTED_REJECTED_STATUS = Status::REFUND_REPLACEMENT_REQUESTED_REJECTED_STATUS;
    const  REFUND_PROGRESSING_STATUS = Status::REFUND_PROGRESSING_STATUS;
    const  REPLACEMENT_PROGRESSING_STATUS = Status::REPLACEMENT_PROGRESSING_STATUS;
    const  REFUND_BALANCE_STATUS = Status::REFUND_BALANCE_STATUS;
    const  REFUND_STATUS = Status::REFUND_STATUS;
    const  REPLACEMENT_STATUS = Status::REPLACEMENT_STATUS;
    const  READY_STATUS = Status::READY_STATUS;
    const  ONHOLD_STATUS = Status::ONHOLD_STATUS;
    const  RETURN_BALANCE_STATUS = Status::RETURN_BALANCE_STATUS;


    public static function getAllStatuses(): array
    {
        $statuses = [];
        $reflectionClass = new \ReflectionClass(self::class);

        foreach ($reflectionClass->getConstants() as $constant => $value) {
            $statuses[] = [
                'id' => $value,
                'name' => self::getHumanReadableOrderStatusName($value),
                'icon' => self::getOrderStatusIcon($value),
            ];
        }
        return $statuses;
    }

    public static function getAllFilterStatuses(): array
    {
        $statuses = [
            [
                'id' => self::NEW_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::NEW_STATUS),
                'icon' => self::getOrderStatusIcon(self::NEW_STATUS),
            ],
            [
                'id' => self::PAY_PENDING_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::PAY_PENDING_STATUS),
                'icon' => self::getOrderStatusIcon(self::PAY_PENDING_STATUS),
            ],
            [
                'id' => self::PENDING_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::PENDING_STATUS),
                'icon' => self::getOrderStatusIcon(self::PENDING_STATUS),
            ],
            [
                'id' => 'validated',
                'name' => 'Validated',
                'icon' => 'fa fa-check',
            ],
            [
                'id' => self::ONHOLD_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::ONHOLD_STATUS),
                'icon' => self::getOrderStatusIcon(self::ONHOLD_STATUS),
            ],
            [
                'id' => self::PENDING_INVENTORY_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::PENDING_INVENTORY_STATUS),
                'icon' => self::getOrderStatusIcon(self::PENDING_INVENTORY_STATUS),
            ],
            [
                'id' => self::PREPARING_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::PREPARING_STATUS),
                'icon' => self::getOrderStatusIcon(self::PREPARING_STATUS),
            ],
            [
                'id' => self::SHIPPING_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::SHIPPING_STATUS),
                'icon' => self::getOrderStatusIcon(self::SHIPPING_STATUS),
            ],
            [
                'id' => self::CANCELED_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::CANCELED_STATUS),
                'icon' => self::getOrderStatusIcon(self::CANCELED_STATUS),
            ],
            [
                'id' => self::COMPLETED_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::COMPLETED_STATUS),
                'icon' => self::getOrderStatusIcon(self::COMPLETED_STATUS),
            ],
            [
                'id' => self::RETURN_BALANCE_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::RETURN_BALANCE_STATUS),
                'icon' => self::getOrderStatusIcon(self::RETURN_BALANCE_STATUS),
            ],
            [
                'id' => self::REJECTED_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::REJECTED_STATUS),
                'icon' => self::getOrderStatusIcon(self::REJECTED_STATUS),
            ],
            [
                'id' => self::REFUND_REPLACEMENT_REQUESTED_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::REFUND_REPLACEMENT_REQUESTED_STATUS),
                'icon' => self::getOrderStatusIcon(self::REFUND_REPLACEMENT_REQUESTED_STATUS),
            ],
            [
                'id' => self::REFUND_PROGRESSING_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::REFUND_PROGRESSING_STATUS),
                'icon' => self::getOrderStatusIcon(self::REFUND_PROGRESSING_STATUS),
            ],[
                'id' => self::REFUND_BALANCE_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::REFUND_BALANCE_STATUS),
                'icon' => self::getOrderStatusIcon(self::REFUND_BALANCE_STATUS),
            ],
            [
                'id' => self::REFUND_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::REFUND_STATUS),
                'icon' => self::getOrderStatusIcon(self::REFUND_STATUS),
            ],
            [
                'id' => self::REPLACEMENT_PROGRESSING_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::REPLACEMENT_PROGRESSING_STATUS),
                'icon' => self::getOrderStatusIcon(self::REPLACEMENT_PROGRESSING_STATUS),
            ],
            [
                'id' => self::REPLACEMENT_STATUS,
                'name' => self::getHumanReadableOrderStatusName(self::REPLACEMENT_STATUS),
                'icon' => self::getOrderStatusIcon(self::REPLACEMENT_STATUS),
            ],
        ];

        return $statuses;
    }


    public static function getHumanReadableOrderStatusName($case): string
    {
        switch ($case) {
            case Status::NEW_STATUS:
                return 'New';
            case Status::PENDING_STATUS:
                return 'Pending confirmation';
            case Status::SHIPPING_STATUS:
                return 'Shipping';
            case Status::REJECTED_STATUS:
                return 'Rejected';
            case Status::COMPLETED_STATUS:
                return 'Completed';
            case Status::CANCELED_STATUS:
                return 'Canceled';
            case Status::REFUND_BALANCE_STATUS:
                return 'Refund Balance';
            case Status::PAY_PENDING_STATUS:
                return 'Pending payment';
            case Status::READY_STATUS:
                return 'Ready';
            case Status::PREPARING_STATUS:
                return 'Preparing';
            case Status::PENDING_INVENTORY_STATUS:
                return 'Pending inventory';
            case Status::REFUND_REPLACEMENT_REQUESTED_STATUS:
                return 'Refund Replacement Requested';
            case Status::REFUND_REPLACEMENT_REQUESTED_REJECTED_STATUS:
                return 'Refund Replacement Requested Rejected';
            case Status::REFUND_PROGRESSING_STATUS:
                return 'Refund Progressing';
            case Status::REPLACEMENT_PROGRESSING_STATUS:
                return 'Replacement Progressing';
            case Status::REFUND_BALANCE_STATUS:
                return 'Refund Balance';
            case Status::REFUND_STATUS:
                return 'Refund';
            case Status::REPLACEMENT_STATUS:
                return 'Replacement';
            case Status::ONHOLD_STATUS:
                return 'On Hold';
            case Status::RETURN_BALANCE_STATUS:
                return 'Return Balance';
            default:
                return $case;
        }
    }


    public static function getOrderStatusIcon($case): string
    {
        switch ($case) {
            case Status::NEW_STATUS:
                return 'fas fa-star';
            case Status::PENDING_STATUS:
                return 'fas fa-clock';
            case Status::SHIPPING_STATUS:
                return 'fas fa-shipping-fast';
            case Status::REJECTED_STATUS:
                return 'fas fa-times-circle';
            case Status::COMPLETED_STATUS:
                return 'fas fa-check-circle';
            case Status::CANCELED_STATUS:
                return 'fas fa-ban';
            case Status::PAY_PENDING_STATUS:
                return 'fas fa-money-bill';
            case Status::PREPARING_STATUS:
                return 'fas fa-gift';
            case Status::PENDING_INVENTORY_STATUS:
                return 'fas fa-boxes';
            case Status::REFUND_REPLACEMENT_REQUESTED_STATUS:
                return 'fas fa-exchange-alt';
            case Status::REFUND_REPLACEMENT_REQUESTED_REJECTED_STATUS:
                return 'fas fa-exchange-alt';
            case Status::REFUND_PROGRESSING_STATUS:
                return 'fas fa-exchange-alt';
            case Status::REPLACEMENT_PROGRESSING_STATUS:
                return 'fas fa-exchange-alt';
            case Status::REFUND_BALANCE_STATUS:
                return 'fas fa-exchange-alt';
            case Status::REFUND_STATUS:
                return 'fas fa-exchange-alt';
            case Status::REPLACEMENT_STATUS:
                return 'fas fa-exchange-alt';
            case Status::ONHOLD_STATUS:
                return 'fas fa-exchange-alt';
            case Status::RETURN_BALANCE_STATUS:
                return 'fas fa-money-bill';
            default:
                return 'fas fa-question';
        }
    }
}
