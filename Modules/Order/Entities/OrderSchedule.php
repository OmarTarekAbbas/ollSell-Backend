<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderSchedule extends Model
{
    protected $fillable = [
        'order_id',
        'scheduled_date',
        'satisfied'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
