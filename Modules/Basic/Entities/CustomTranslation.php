<?php

namespace Modules\Basic\Entities;

use Illuminate\Database\Eloquent\Model;
//todo change
class CustomTranslation extends Model
{
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    protected $fillable = [
        'status', 'key'
    ];
    protected $table = 'custom_translations';
    public $timestamps = true;

    public $searchRelationShip  = [];
    public static $rules = [
        'key' => 'required|string|unique:custom_translations',
    ];

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['value'];

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
     * This PHP function returns the value of a translation for a given language.
     * 
     * param lang  is a variable that represents a language object. It is likely an instance of a
     * Language model or class that contains information about a specific language, such as its name,
     * code, and ID. The function uses this language object to retrieve the translation value for a
     * specific language.
     * 
     * return the value of the translation for the given language ID.
     */
    public function valueValue($lang)
    {
        return $this->translation()->where('language_id', $lang->id)->first()->value;
    }

    /**
     * The function returns an array with a single string value 'value'.
     * 
     * return An array with one element, the string 'value'.
     */
    public static function translationKey()
    {
        return ['value'];
    }

    /**
     * This function returns a morphMany relationship for the Translation model with the category.
     * 
     * return A morphMany relationship between the current model and the Translation model, where the
     * category column of the Translation model is used to determine the type of the related model.
     */
    public function translation()
    {
        return $this->morphMany(Translation::class, 'category');
    }

    /**
     * This PHP function returns a value from a translation table based on the category, key, and
     * language ID.
     * 
     * return The `value()` function is returning a query builder instance that is filtering records
     * from the `translations` table based on the `category`, `key`, and `language_id` columns.
     * Specifically, it is filtering records where the `key` column has the value `'value'` and the
     * `language_id` column has the value returned by the `languageId()` function. The `morphone()`
     */
    public function value()
    {
        return $this->morphone(Translation::class, 'category')
            ->where('key', 'value')
            ->where('language_id', languageId());
    }

    /**
     * This function deletes the translation associated with a custom translation when the custom
     * translation is deleted.
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($customTranslation) {
            $customTranslation->translation()->delete();
        });
    }
}
