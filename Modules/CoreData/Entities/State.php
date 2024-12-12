<?php

namespace Modules\CoreData\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Basic\Entities\Translation;

class State extends Model
{
    protected $fillable = [
        'status', 'order', 'city_id', 'country_id'
    ];
    protected $table = 'states';
    public $timestamps = true;
    public $searchRelationShip = [];

    /**
     * [columns that needs to has search such as like or where in]
     *
     * @var string[]
     */
    public static $rules = [
        // 'order' => 'required|numeric',
        'country_id' => 'required|exists:countries,id',
        'city_id' => 'required|exists:cities,id',
    ];
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['name'];

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
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];

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
     * return A morphMany relationship between the current model and the Translation model, where the
     * current model is the parent and the Translation model is the child. The relationship allows the
     * current model to have multiple translations associated with it.
     */
    public function translation()
    {
        return $this->morphMany(Translation::class, 'category');
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
     * This is a PHP function that defines a relationship between a model and a country.
     * 
     * return A relationship between the current model and the `Country` model, where the foreign key
     * `country_id` in the current model references the primary key `id` in the `Country` model.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * This PHP function returns a relationship between the current object and a City model.
     * 
     * return A relationship between the current model and the City model, where the foreign key
     * 'city_id' in the current model corresponds to the primary key 'id' in the City model.
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * This function deletes the translation of a state when the state is deleted.
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($state) {
            $state->translation()->delete();
        });
    }
}
