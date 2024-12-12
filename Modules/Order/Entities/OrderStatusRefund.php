<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\CoreData\Entities\Status;

class OrderStatusRefund extends Model
{
    /* A white list of attributes that are allowed to be mass assigned. */
    protected $fillable = [
        'order_refund_id', 'status_id'
    ];

    /* Telling the model to use the profits table. */
    protected $table = 'order_status_refunds';

    /* Telling the model to use the timestamps created_at and updated_at. */
    public $timestamps = true;

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];
    public $searchRelationShip = [];
    public static $rules = [];

    /**
     * This function returns the validation rules for the model.
     * 
     * return The rules for the model.
     */
    public static function getValidationRules()
    {
        return self::$rules;
    }

    /**
     * This function returns an array of all the keys that are used in the translation files.
     * 
     * return An array of the keys that are to be translated.
     */
    public static function translationKey()
    {
        return [];
    }

    /**
     * This order function returns the order that belongs to this order_item.
     * 
     * return The order() method returns the order that belongs to the order_id.
     */
    public function order()
    {
      return $this->belongsTo(Order::class,'order_id');
    }

    /**
     * This Status function returns the order that belongs to this order_item.
     * 
     * return The Status() method returns the order that belongs to the order_id.
     */
    public function status()
    {
      return $this->belongsTo(Status::class,'status_id');
    }

    /**
     * This order function returns the order that belongs to this order_item.
     * 
     * return The order() method returns the order that belongs to the order_id.
     */
    public function orderRefund()
    {
      return $this->belongsTo(OrderRefund::class,'order_refund_id');
    }
}
