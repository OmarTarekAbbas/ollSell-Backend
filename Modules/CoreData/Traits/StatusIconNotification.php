<?php

namespace Modules\CoreData\Traits;

class StatusIconNotification
{

    /**
     * The function `statusIcon` returns the path to an icon image based on the given type.
     * 
     * param type The parameter "type" is a string that represents the type of status icon. It can
     * have the following values:
     * 
     * @return string a string representing the file path of an icon image based on the given type.
     */
    public static function statusIcon($type): string
    {
        switch ($type) {
            case 'order':
                return asset('assets/icons/orders.png');
            case 'category':
                return asset('assets/icons/coupon.png');
            case 'supplier_suggested_new_category':
                return asset('assets/icons/campaigning.png');
            case 'message':
                return asset('assets/icons/campaigning.png');
            case 'depositRequest':
                return asset('assets/icons/campaigning.png');
            default:
                return '';
        }
    }
}
