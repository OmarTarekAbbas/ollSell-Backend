<?php

namespace Modules\Order\Actions\Order;

use Carbon\Carbon;
use Modules\Acl\Entities\Dropshipper;
use Modules\CoreData\Entities\DropshipperSegmentation;

class DropshipperSegmentationMonthAction
{

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
    public function execute($order)
    {
        $dropshipper = Dropshipper::find($order->dropshipper_id);
        $countOrder  = $dropshipper->order()
        ->whereNotNull('validated')
        ->where('created_at', '>=',  Carbon::now()->subDays(45))
        ->count();
        $segmentation=DropshipperSegmentation::where('from','<=',$countOrder)->where('to','>=',$countOrder)->first();
        $segmentation=($segmentation) ? $segmentation->id :1;
        $dropshipper->dropshipper_segmentation_id = $segmentation;
        $dropshipper->save();
    }
}
