<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'action',
        'user_id',
        'user_type',
        'attribute_changed',
        'old_value',
        'new_value',
    ];


    /**
     * Get the user who performed the action.
     */
    public function user()
    {
        return $this->morphTo();
    }

    /**
     * Get the order associated with the log.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
