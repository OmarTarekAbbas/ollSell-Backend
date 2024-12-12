<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class OllopsInteractionLog extends Model
{
    use Loggable;

    protected $fillable = [
        'order_id',
        'action',
        'details',
    ];

    const ORDER_UPDATE = 'order_update';
    const ORDER_CONFIRMED = 'order_confirmed';
    const ORDER_REJECTED = 'order_rejected';
    const ORDER_CANCELLED = 'order_cancelled';

    /**
     * Get the order associated with the log.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
