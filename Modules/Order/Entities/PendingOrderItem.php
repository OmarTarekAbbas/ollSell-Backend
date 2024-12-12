<?php

namespace Modules\Order\Entities;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;


class PendingOrderItem extends Model
{
    protected $table = 'pending_orders_items';

    protected $fillable = [
        'pending_order_id',
        'sku',
        'quantity',
        'selling_price'
    ];

    /**
     * The `pendingOrder` function defines a relationship where the current object belongs to a
     * `PendingOrder` object.
     * 
     * @return The function `pendingOrder()` is returning a relationship defined by the `belongsTo`
     * method in Laravel's Eloquent ORM. It specifies that the current model belongs to a
     * `PendingOrder` model.
     */
    public function pendingOrder()
    {
        return $this->belongsTo(PendingOrder::class, 'pending_order_id');
    }

    /* Telling the model to use the timestamps created_at and updated_at. */
    public $timestamps = true;

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = ['created_at' => 'date'];
    public $searchRelationShip = [];
}