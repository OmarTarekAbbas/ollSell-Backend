<?php

namespace Modules\Order\Entities;

use Modules\CoreData\Entities\Status;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class OrderRefund extends Model
{
    use Loggable;

    /* A white list of attributes that are allowed to be mass assigned. */
    protected $fillable = [
        // 'order_item_id',
        'status_id', 'order_id', 'reason', 'tracking_number', 'pdf_label', 'deliveryDate', 'totalQuantity', 'countOrderItem', 'grandTotal'
    ];

    /* Telling the model to use the profits table. */
    protected $table = 'order_refunds';

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
     * The function "Order" belongs to a class and returns a relationship to the "Order" class.
     *
     * return a relationship between the current model and the Order model.
     */
    public function orderRefundItems()
    {
        return $this->hasMany(OrderRefundItem::class);
    }

    /**
     * The function "Order" belongs to a class and returns a relationship to the "Order" class.
     *
     * return a relationship between the current model and the Order model.
     */
    public function Order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * This Status function returns the order that belongs to this order_item.
     *
     * return The Status() method returns the order that belongs to the order_id.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * This function returns a collection of OrderStatus objects that are related to the current Order
     * object.
     *
     * return The OrderStatus model.
     */
    public function orderRefund()
    {
        return $this->hasMany(OrderStatusRefund::class);
    }

    public function refundMessages()
    {
        return $this->hasMany(RefundMessage::class);
    }
}
