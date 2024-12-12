<?php

namespace Modules\Order\Enums;

use Modules\Order\Entities\Order;

enum PlatformEnum: int
{
    const SALLA_PLATFORM = Order::SALLA_PLATFORM;
    const EASYORDER_PLATFORM = Order::EASYORDER_PLATFORM;
    const TiKTOK_PLATFORM = Order::TiKTOK_PLATFORM;
    const WEBSITE_PLATFORM = Order::WEBSITE_PLATFORM;
    const ADMIN_PLATFORM = Order::ADMIN_PLATFORM;

    public static function getPleatform($case)
    {
        switch(preg_replace('/\s+/', '', strtolower($case)))
        {
            case self::WEBSITE_PLATFORM:
            case 'ويب سايت':
            case 'ويبسايت':
            case 'سايت':
            case 'ويب':
                return self::WEBSITE_PLATFORM;
            case self::EASYORDER_PLATFORM:
            case 'ايزي اوردر':
            case 'ايزياوردر':
            case 'easy order':
            case 'easy-order':
            case 'easy':
            case 'easyorder':
                return self::EASYORDER_PLATFORM;
            case self::SALLA_PLATFORM:
            case "سله":
            case "سلة":
                return self::SALLA_PLATFORM;
            case self::TiKTOK_PLATFORM:
            case 'tik tok':
            case 'tik took':
            case 'tiktook':
            case 'تيك توك':
            case 'تيكتوك':
                return self::TiKTOK_PLATFORM;
            case self::ADMIN_PLATFORM:
                return self::ADMIN_PLATFORM;
            default:
                return null;
        }
    }

    public static function list()
    {
       return [self::SALLA_PLATFORM, self::EASYORDER_PLATFORM, self::TiKTOK_PLATFORM, self::WEBSITE_PLATFORM];
    }
}
