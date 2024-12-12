<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\MasterCatalog\Entities\Bundle;
use Modules\MasterCatalog\Entities\Product;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class OrderItem extends Model
{
    use Loggable;

    /* A white list of attributes that are allowed to be mass assigned. */
    protected $fillable = [
        'order_id',
        'product_id',
        'bundle_id',
        'quantity',
        'unitPrice',
        'totalPrice',
        'vat',
        'product_json',
        'variant_id',
        'sku',
        'variants_json',
        'country_json',
        'city_json',
        'total_profit',
        'vat_profit',
        'net_profit',
        'net_profit',
        'product_vat',
        'product_details',
        'supplier_id',
        'status_id',
        'is_ready',
        'added_by',
        'bundle_json',
        'bundle_details',
        'discount',
        'is_discount',
    ];
    /* Telling the model to use the profits table. */
    protected $table = 'order_items';
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
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * This function returns the product that belongs to this order.
     *
     * return The product_id of the product that is associated with the order.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'bundle_id');
    }

    /**
     * The function "orderRefundItem" returns a relationship with the "OrderRefundItem" class.
     *
     * return a relationship of type "hasOne" with the model "OrderRefundItem".
     */
    public function orderRefundItem()
    {
        return $this->hasOne(OrderRefundItem::class);
    }

    /**
     * The boot function in PHP is used to update the quantity of a product by subtracting the quantity of
     * a newly created data entry.
     */
    public static function boot()
    {
        parent::boot();
    }
}
