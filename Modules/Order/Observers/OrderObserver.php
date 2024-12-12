<?php

namespace Modules\Order\Observers;

use App\Models\User;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Remark;
use Modules\Order\Enums\OrderEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\Acl\Entities\Dropshipper;
use Modules\CoreData\Actions\Notification\SendNotificationByAdminAction;
use Modules\CoreData\Actions\Notification\SendNotificationByDropshipperAction;
use Modules\CoreData\Entities\Status;
use Modules\CoreData\Traits\StatusColorNotification;
use Modules\Finance\Service\TransactionService;
use Modules\Order\Actions\Order\DropshipperSegmentationMonthAction;
use Modules\Order\Actions\Order\SendOrderToWMSAction;
use Modules\Order\Actions\Order\SyncOllopsOrderAction;
use Modules\Order\Entities\FollowUp;
use Modules\Order\Entities\OrderLog;
use Modules\Order\Entities\SubStatus;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Service\InvoiceService;
use Modules\Order\Service\OrderItemService;
use Modules\Order\Service\OrderStatusService;
use Modules\Webhooks\Entities\Webhook;
use Modules\Order\Actions\OrderStatus\ChangeOrderStatusInSallaAction;
class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * param  \App\Models\Order  $order
     * return void
     */
    public function created(Order $order)
    {
        app()->make(OrderItemService::class)->store($order);
        app()->make(InvoiceService::class)->store($order);
        $title = json_encode([
            'en' => 'New Order #' . $order->id,
            'ar' => "طلب جديد #" . $order->id,
        ]);
        $content = json_encode([
            'en' =>  ' New order with id # ' . $order->id . ' has been created.',
            'ar' =>  " طلب جديد برقم # " . $order->id . ' تم أنشاء .',
        ]);
        $urlType = 'order';
        $urlId = $order->id;
        $color = StatusColorNotification::statusColor($order->status_id);
        App(SendNotificationByAdminAction::class)->execute($title, $content, $urlType, $urlId, $color);
        app()->make(OrderStatusService::class)->store($order);
        App(DropshipperSegmentationMonthAction::class)->execute($order);
    }


    /**
     * Handle the Order "updated" event.
     *
     * param  \App\Models\Order  $order
     * return void
     */
    public function updated(Order $order)
    {
        app()->make(OrderItemService::class)->updateStatusOrderItem($order);
        if($order->isDirty('status_id') && $order->status_id == OrderEnum::COMPLETED_STATUS)
        {
            // app()->make(WalletService::class)->store($order); //TODO::cron job for transactions.
            app()->make(TransactionService::class)->store($order);
        }

        // Get the list of registered webhooks for the event 'order.updated'
        $webhooks = Webhook::where('event', 'order.updated')->get();
        // Loop through the webhooks and send POST requests
        foreach($webhooks as $webhook)
        {
            $this->sendWebhookRequest($webhook->url, $order->toArray());
        }
        if($order->isDirty('status_id'))
        {
            if($order->source_platform == 'salla')
            {
                App(ChangeOrderStatusInSallaAction::class)->execute($order);
            }
            $orderStatus = OrderStatus::where('order_id', $order->id)->where('status_id', $order->status_id)->first();
            if(!$orderStatus)
            {
                app()->make(OrderStatusService::class)->store($order);
            }
            $title = json_encode([
                'en' => ' Order # ' . $order->id . ' status changed ',
                'ar' => ' طلب # ' . $order->id . " تغيرت الحالة",
            ]);
            $content = json_encode([
                'en' => ' Order # ' . $order->id . ' changed to ' . getStatusName($order->status_id),
                'ar' => ' طلب # ' . $order->id . ' تغير إلى ' . getStatusName($order->status_id),
            ]);
            $urlType = 'order';
            $urlId = $order->id;
            $color = StatusColorNotification::statusColor($order->status_id);
            App(SendNotificationByDropshipperAction::class)->execute($title, $content, $order->dropshipper_id, $urlType,
                $urlId, $color);
            App(DropshipperSegmentationMonthAction::class)->execute($order);
        }
        if($order->isDirty('tracking_number'))
        {
            if($order->tracking_number)
            {
                App(SendOrderToWMSAction::class)->execute($order);
            }
        }
    }

    public function updating(Order $order)
    {
        if($order->isDirty('status_id'))
        {
            $this->logOrderChange($order, 'status_id', $order->getOriginal('status_id'), $order->status_id);
            $this->createFollowUp($order, 'Attribute changed', 'status_id', $order->getOriginal('status_id'),
                $order->status_id);
            // check if ollops need to change status
            $oldStatus = $order->getOriginal('status_id');
            $newStatus = $order->status_id;
            if($order->sent_to_ollops_at && $order->ollops_confirmation_status == 'pending' && ($oldStatus == OrderEnum::PENDING_STATUS || $oldStatus == OrderEnum::NEW_STATUS) && $newStatus != OrderEnum::PENDING_STATUS)
            {
                (new SyncOllopsOrderAction($order, $oldStatus, $newStatus))->execute();
            }
        }
        if($order->isDirty('sub_status_id'))
        {
            $this->logOrderChange($order, 'sub_status_id', $order->getOriginal('sub_status_id'), $order->sub_status_id);
            if(!$order->isDirty('status_id'))
            {
                $this->createFollowUp($order, 'Attribute changed', 'sub_status_id',
                    $order->getOriginal('sub_status_id'), $order->sub_status_id);
            }
        }
        if($order->isDirty('remark_id'))
        {
            $this->logOrderChange($order, 'remark_id', $order->getOriginal('remark_id'), $order->remark_id);
            if(!$order->isDirty('status_id') && !$order->isDirty('sub_status_id'))
            {
                $this->createFollowUp($order, 'Attribute changed', 'remark_id', $order->getOriginal('remark_id'),
                    $order->remark_id);
            }
        }
    }


    protected function createFollowUp(Order $order, $activityType, $attribute, $oldValue, $newValue)
    {
        $user = Auth::guard('web')->user() ?? User::find(1);
        $userName = $user ? $user->name : 'Unknown User';
        $oldValue = $this->getAttributeName($attribute, $oldValue);
        $newValue = $this->getAttributeName($attribute, $newValue);
        $attributeDisplayName = $this->getAttributeDisplayName($attribute);
        $content = "$userName updated the $attributeDisplayName of the order:\n";
        $content .= "Changed from ($oldValue)\n";
        $content .= "to ($newValue)";
        FollowUp::create([
            'order_id' => $order->id,
            'user_id' => $user ? $user->id : null,
            'activity_type' => $activityType,
            'content' => $content,
        ]);
    }

    protected function getAttributeName($attribute, $value)
    {
        switch($attribute)
        {
            case 'status_id':
                return Status::find($value)?->name?->value ?? '-';
            case 'sub_status_id':
                return SubStatus::find($value)?->name ?? '-';
            case 'remark_id':
                return Remark::find($value)?->name ?? '-';
            // Add more cases for other attributes if needed
            default:
                return $value;
        }
    }

    protected function getAttributeDisplayName($attribute)
    {
        switch($attribute)
        {
            case 'status_id':
                return 'Status';
            case 'sub_status_id':
                return 'SubStatus';
            case 'remark_id':
                return 'Remark';
            default:
                return $attribute;
        }
    }

    /**
     * Log order attribute change.
     *
     * param  \App\Order  $order
     * param  string  $attribute
     * param  mixed  $oldValue
     * param  mixed  $newValue
     * return void
     */
    protected function logOrderChange(Order $order, $attribute, $oldValue, $newValue)
    {
        OrderLog::create([
            'order_id' => $order->id,
            'action' => 'Attribute changed',
            'user_id' => user()?->id ?? 0,
            'user_type' => user() instanceof Dropshipper ? 'Modules\Acl\Entities\Dropshipper' : 'App\Models\User',
            'attribute_changed' => $attribute,
            'old_value' => $oldValue,
            'new_value' => $newValue,
        ]);
    }

    /**
     * The function `sendWebhookRequest` sends a POST request to a specified URL with provided data and
     * handles the response accordingly.
     *
     * param url The URL where the webhook request will be sent.
     * param data The `` parameter is the payload or data that you want to send in the webhook
     * request. It can be an array or an object containing the necessary information that the webhook
     * endpoint expects.
     */
    private function sendWebhookRequest($url, $data)
    {
        try
        {
            Http::post($url, $data);

        }catch(\Exception $e)
        {
            // Exception occurred while sending the webhook request
        }
    }

    /**
     * Handle the Order "deleted" event.
     *
     * param  \App\Models\Order  $order
     * return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * param  \App\Models\Order  $order
     * return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * param  \App\Models\Order  $order
     * return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
