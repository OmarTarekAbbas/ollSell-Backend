<?php

namespace Modules\CoreData\Entities;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Modules\Basic\Entities\Translation;
use Modules\Logistics\Entities\ShippingCompanyCityTime;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\PendingOrder;

class City extends Model
{
    use Loggable;

    /* The `protected ` property is used in Laravel's Eloquent ORM to specify which attributes
    of a model are fillable. In this case, the `City` model can have its `status` and `country_id`
    attributes filled with data from a form or other input source. Any other attributes not listed
    in `` will be guarded and cannot be mass assigned. This is a security feature to
    prevent unintended data manipulation. */
    protected $fillable = [
        'status', 'country_id'
    ];
    protected $table = 'cities';
    public $timestamps = true;
    public $searchRelationShip = [
        'alias'=>'alias->alias->like'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['name'];

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [
        
        'name' => 'like',
    ];

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public static $rules = [
        'country_id' => 'required|exists:countries,id',
    ];

    /**
     * This function returns the validation rules.
     *
     * return The `getValidationRules()` function is returning the static property ``.
     */
    public static function getValidationRules()
    {
        return self::$rules;
    }

    /**
     * The function returns an array containing a single string value 'name'.
     *
     * return An array containing the string "name".
     */
    public static function translationKey()
    {
        return ['name'];
    }

    /**
     * This is a PHP function that defines a relationship between the current model and the Country
     * model.
     *
     * return A relationship between the current model and the `Country` model is being returned.
     * Specifically, it is a `belongsTo` relationship, indicating that the current model belongs to a
     * single instance of the `Country` model.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * This PHP function returns a morphMany relationship for a Translation model with a category.
     *
     * return The `translation()` function is returning a morphMany relationship between the current
     * model and the `Translation` model, where the current model is the parent of the relationship and
     * the `Translation` model is the child. The relationship is defined by the `category` morphable
     * type and is used to retrieve all translations associated with the current model.
     */
    public function translation()
    {
        return $this->morphMany(Translation::class, 'category');
    }

    /**
     * This PHP function returns a query builder instance for a specific translation category and
     * filters by name and language ID.
     *
     * return a query builder instance that is filtering the translations table by the 'category'
     * column and the 'name' key, as well as the current language ID.
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
    public function nameValueId($langId)
    {
        return $this->translation()->where('language_id', $langId)->first()->value;
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
     * The function returns the translation for the key 'name'.
     *
     * return a query builder instance for the translation table where the key column is equal to
     * 'name'.
     */
    public function getTotalTranslation()
    {
        return $this->translation()->where('key', 'name');
    }

    public function alias()
    {
        return $this->hasMany(CityAlias::class);
    }

     /**
     * This function returns a collection of all the orders that belong to this user.
     *
     * return A collection of Order objects.
     */
    public function pendingOrder()
    {
        return $this->hasMany(PendingOrder::class);
    }

    public function ShippingCompanyCityTime()
    {
        return $this->hasMany(ShippingCompanyCityTime::class);
    }
}
