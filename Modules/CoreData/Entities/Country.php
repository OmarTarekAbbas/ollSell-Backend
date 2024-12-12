<?php

namespace Modules\CoreData\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Basic\Entities\Media;
use Modules\Basic\Entities\Translation;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\PendingOrder;

class Country extends Model
{
    /* `` is an array that specifies which attributes of the model are mass assignable. In
    this case, it allows the `status`, `order`, and `code` attributes to be set in bulk using the
    `create` or `update` methods. All other attributes will be protected from mass assignment. */
    protected $fillable = [
        'status',
        'order',
        'code'
    ];
    protected $table = 'countries';
    public $timestamps = true;
    public $searchRelationShip = [];

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
        'code' => 'like',
    ];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public static $rules = [
        'order' => 'required|numeric|unique:countries',
        'code' => 'required|string|unique:countries',
        'logo' => 'image|mimes:jpg,jpeg,png,gif',
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
     * This PHP function returns a morphMany relationship for a Translation model with a category.
     * 
     * return The function `translation()` is returning a morphMany relationship between the current
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
     * The function returns the translation for the key 'name'.
     * 
     * return a query builder instance for the translation table where the key column is equal to
     * 'name'.
     */
    public function getTotalTranslation()
    {
        return $this->translation()->where('key', 'name');
    }

    /**
     * This function returns a morphOne relationship with the Media model under the category attribute.
     * 
     * return The `media()` function is returning a `morphOne` relationship between the current model
     * and the `Media` model, where the `category` column of the `Media` table is used to store the
     * type of the related model.
     */
    public function media()
    {
        return $this->morphOne(Media::class, 'category');
    }

    /**
     * The function returns the logo media of an object.
     * 
     * return The `logo()` function is returning a query builder instance that retrieves the media
     * associated with the current object (presumably a model) where the media type is equal to the
     * value of the `lm` key in the `mediaType()` array.
     */
    public function logo()
    {
        return $this->media()->whereType(mediaType()['lm']);
    }

    /**
     * This function deletes related data when the main data is deleted.
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($data) {
            $data->city()->delete();
            $data->translation()->delete();
            $data->media()->delete();
        });
    }

    /**
     * This PHP function defines a one-to-many relationship between a model and its related City
     * models.
     * 
     * return A relationship between the current model and the City model is being returned.
     * Specifically, a "has many" relationship, indicating that the current model can have multiple
     * instances of the City model associated with it.
     */
    public function city()
    {
        return $this->hasMany(City::class);
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
    public function pendingOrder()
    {
        return $this->hasMany(PendingOrder::class);
    }
}
