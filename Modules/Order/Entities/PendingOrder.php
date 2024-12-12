<?php

namespace Modules\Order\Entities;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Modules\Acl\Entities\Dropshipper;
use Modules\CoreData\Entities\City;
use Modules\CoreData\Entities\Country;

class PendingOrder extends Model
{
    use Loggable;

    protected $table = 'pending_orders';

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_address',
        'district',
        'city_id',
        'country_id',
        'source_platform',
        'payment_method',
        'duplicated_order_ids',
        'is_duplicated',
        'invalid',
        'message',
        'dropshipper_id',
        'customer_city',
        'customer_country',
    ];


    /* Telling the model to use the timestamps created_at and updated_at. */
    public $timestamps = true;

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = ['created_at' => 'date'];
    public $searchRelationShip = []; 
    public static $rules = [
        'customer_name' => 'required|min:3|max:50',
        'customer_phone' => 'required|size:10',
        'customer_address' => 'required',
        'district' => 'required',
        'customer_city' => 'required',
        'customer_country' => 'required',
        'source_platform' => 'nullable|string',
        'payment_method' => 'required|integer',
        'items.*.sku' => 'required|exists:products,sku',
        'items.*.quantity' => 'required|integer',
        'items.*.selling_price' => 'required',
    ];

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
     * This function returns the country that belongs to the customer.
     *
     * return The country() method returns a relationship between the Customer model and the Country
     * model.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * This is a PHP function that returns a relationship between a model and a Dropshipper model.
     *
     * return The function `dropshipper()` is returning a `belongsTo` relationship between the current
     * model and the `Dropshipper` model, with the foreign key `dropshipper_id`.
     */
    public function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class, 'dropshipper_id');
    }

    /**
     * This function returns the country that belongs to the customer.
     *
     * return The country() method returns a relationship between the Customer model and the Country
     * model.
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * This function returns a collection of OrderItem objects that are related to this Order object.
     *
     * return A collection of OrderItem objects.
     */
    public function pendingOrderItems()
    {
        return $this->hasMany(PendingOrderItem::class);
    }
}
