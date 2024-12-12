<?php

namespace Modules\Order\Entities;

use Modules\CoreData\Entities\Status;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class OrderRefundItem extends Model
{
    use Loggable;
    /* A white list of attributes that are allowed to be mass assigned. */
    protected $fillable = [
        'order_item_id', 'order_refund_id', 'quantity', 'totalPrice'
    ];

    /* Telling the model to use the profits table. */
    protected $table = 'order_refund_items';

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
     * The function "orderItem" returns a relationship between the current object and the "OrderItem"
     * class.
     *
     * return a relationship between the current model and the OrderItem model.
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
