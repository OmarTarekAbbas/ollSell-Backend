<?php

namespace Modules\Order\Observers;

use Modules\Order\Entities\Order;
use Modules\Order\Entities\PendingOrder;
use Modules\Order\Service\PendingOrderItemService;

class PendingOrderImportObserver
{
    /**
     * Handle the Order "created" event.
     *
     * param  \App\Models\Order  $order
     * return void
     */
    public function created(PendingOrder $pendingOrder)
    {
        app()->make(PendingOrderItemService::class)->store($pendingOrder);
    }

    /**
     * Handle the Order "updated" event.
     *
     * param  \App\Models\Order  $order
     * return void
     */
    public function updated(PendingOrder $pendingOrder) {
        app()->make(PendingOrderItemService::class)->store($pendingOrder);
    }

    public function updating(PendingOrder $pendingOrder) {}

    /**
     * Handle the Order "deleted" event.
     *
     * param  \App\Models\Order  $order
     * return void
     */
    public function deleted(PendingOrder $pendingOrder)
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * param  \App\Models\Order  $order
     * return void
     */
    public function restored(PendingOrder $pendingOrder)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * param  \App\Models\Order  $order
     * return void
     */
    public function forceDeleted(PendingOrder $pendingOrder)
    {
        //
    }
}
