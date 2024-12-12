<?php

namespace Modules\Order\Integration\Models;

use Illuminate\Database\Eloquent\Model;

class ClickPaymentLog extends Model
{

    /* A white list of attributes that are allowed to be mass assigned. */
    protected $fillable = [
        'data',
        'order_id',
    ];

    /* Telling the model to use the profits table. */
    protected $table = 'click_payment_logs';
}
