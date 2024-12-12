<?php

namespace Modules\CoreData\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Basic\Entities\Translation;
use Modules\Order\Entities\AttemptsLog;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Entities\OrderStatusRefund;
use Modules\Order\Entities\SubStatus;

//todo change
class Status extends Model
{
    /**
     * Order statuses list
     *
     * @const string
     */
    const NEW_STATUS = 1;
    const PAY_PENDING_STATUS = 11;
    const PENDING_STATUS = 2;
    const PENDING_INVENTORY_STATUS = 17;
    const PREPARING_STATUS = 13;
    const SHIPPING_STATUS = 10;
    const COMPLETED_STATUS = 4;
    const REJECTED_STATUS = 3;
    const CANCELED_STATUS = 5;
    const REFUND_REPLACEMENT_REQUESTED_STATUS = 6;
    const REFUND_REPLACEMENT_REQUESTED_REJECTED_STATUS = 8;
    const REFUND_PROGRESSING_STATUS = 7;
    const REPLACEMENT_PROGRESSING_STATUS = 9;
    const REFUND_BALANCE_STATUS = 12;
    const REFUND_STATUS = 15;
    const REPLACEMENT_STATUS = 16;
    const READY_STATUS = 14;
    const ONHOLD_STATUS = 18;
    const RETURN_BALANCE_STATUS = 19;

    /* A white list of attributes that are allowed to be mass assigned. */
    protected $fillable = [
        'status', 'is_report'
    ];
    /* Telling the model to use the profits table. */
    protected $table = 'status';
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
        return ['name'];
    }

    /**
     * This PHP function returns a morphMany relationship for a Translation model with a status
     * attribute.
     *
     * return A morphMany relationship between the current model and the Translation model is being
     * returned. This means that the current model can have multiple translations associated with it,
     * and the Translation model can belong to multiple types of models (polymorphic relationship).
     */
    public function translation()
    {
        return $this->morphMany(Translation::class, 'category');
    }

    /**
     * This PHP function returns a query builder object for a specific translation record based on the
     * key and language ID.
     *
     * return a query builder instance that is filtering the `status` column of the `Translation`
     * model by `key` and `language_id`. It is not actually returning any data yet, as it needs to be
     * executed with a method like `get()` or `first()` to retrieve the results.
     */
    public function name()
    {
        return $this->morphone(Translation::class, 'category')
            ->where('key', 'name')
            ->where('language_id', languageId());
    }

    /**
     * This PHP function returns the value of a translation based on the language ID.
     *
     * param lang  is a variable that represents the language object. It is likely an instance of
     * a Language model or class that contains information about a specific language, such as its name,
     * code, and ID. The function uses this parameter to retrieve the translation value for a specific
     * language.
     *
     * return the value of the first translation record that matches the given language ID.
     */
    public function nameValue($lang)
    {
        return $this->translation()->where('language_id', $lang->id)->first()->value;
    }

    /**
     * This function deletes the translation of a data object when the data object is deleted.
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($data) {
            $data->translation()->delete();
        });
    }

    /**
     * This function returns a collection of all the orders that belong to this user.
     *
     * return A collection of Order objects.
     */
    public function order()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * This function returns a collection of all the orders that belong to this user.
     *
     * return A collection of Order objects.
     */
    public function orderRefund()
    {
        return $this->hasMany(OrderStatusRefund::class);
    }

    /**
     * This function returns a collection of OrderStatus objects that are related to the current Order
     * object.
     *
     * return The OrderStatus model.
     */
    public function orderStatus()
    {
        return $this->hasMany(OrderStatus::class);
    }

    public function subStatuses()
    {
        return $this->hasMany(SubStatus::class);
    }

    /**
     * This function returns a collection of all the orders that belong to this user.
     *
     * return A collection of Order objects.
     */
    public function attemptsLog()
    {
        return $this->hasMany(AttemptsLog::class);
    }
}
