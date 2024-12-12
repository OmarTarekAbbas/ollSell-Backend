<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Acl\Entities\Dropshipper;
use Modules\Order\Entities\Order;

class Transaction extends Model
{
    /* `protected ` is an array that specifies which attributes of the model are fillable. In
    this case, it allows the specified attributes to be mass assignable, meaning they can be set in
    a single line of code using an array. This is a security feature to prevent unintended
    modification of other attributes. */
    protected $fillable = ['paymentMethod', 'totalOrder', 'sellingPrice', 'costPrice', 'profitRatio', 'order_id', 'dropshipper_id', 'isStatus', 'withdrawal_request_id',
        'earning_type','earning_date'];
    protected $table = 'transactions';
    public $timestamps = true;
    public $searchRelationShip = [];
    public $searchConfig = ['created_at' => 'date','earning_date'=>'date'];
    /**
     * The function defines a "belongsTo" relationship between the current model and the Order model in
     * PHP.
     *
     * return A "belongsTo" relationship between the current model and the Order model is being
     * returned.
     */
    public function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class);
    }

    /**
     * This function defines a relationship between the current model and the Order model in PHP.
     *
     * return A relationship between the current model and the Order model is being returned.
     * Specifically, it is a "belongsTo" relationship, indicating that the current model belongs to an
     * instance of the Order model.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
