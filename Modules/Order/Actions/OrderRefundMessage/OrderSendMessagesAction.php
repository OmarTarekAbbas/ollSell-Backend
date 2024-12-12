<?php

namespace Modules\Order\Actions\OrderRefundMessage;

use Modules\Order\Repositories\RefundMessageRepository;
use Modules\Order\Http\Resources\Order\RefundMessageResource;
use Modules\CoreData\Actions\Notification\SendNotificationByAdminAction;
use Modules\CoreData\Actions\Notification\SendNotificationByDropshipperAction;

class OrderSendMessagesAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(RefundMessageRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function executes an orderRefund by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the orderRefund data.
     * 
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     * 
     * @return RefundMessageResource RefundMessageResource
     */
    public function execute($request, $id)
    {
        $sendMessages = user()->refundMessages()->create([
            'message' => $request->message,
            'order_refund_id' => $id,
        ]);

        if (user()->accountType() === 'App\Models\User') {

            $title = json_encode([
                'en' => 'New Message By Refund Order',
                'ar' => 'رسالة جديدة عن طريق طلب استرداد الأموال',
            ]);

            $content = json_encode([
                'en' => $request->message,
                'ar' => $request->message,
            ]);


            $urlType = 'message';
            $urlId = $sendMessages->id;
            $color = '#1E90FF';
            App(SendNotificationByDropshipperAction::class)->execute($title, $content, user()->id, $urlType, $urlId);
        } else {

            $title = json_encode([
                'en' => 'New Message By Refund Order',
                'ar' => 'رسالة جديدة عن طريق طلب استرداد الأموال',
            ]);

            $content = json_encode([
                'en' => $request->message,
                'ar' => $request->message,
            ]);

            $urlType = 'message';
            $urlId = $sendMessages->id;
            $color = '#1E90FF';
            App(SendNotificationByAdminAction::class)->execute($title, $content, $urlType, $urlId, $color);
        }

        return new RefundMessageResource($sendMessages);
    }
}
