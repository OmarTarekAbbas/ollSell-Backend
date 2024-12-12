<?php

namespace Modules\CoreData\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Basic\Entities\Translation;

class DropshipperSegmentation extends Model
{
    /* `` is an array that specifies which attributes of the model are mass assignable. In
    this case, it allows the `status`, `order`, and `code` attributes to be set in bulk using the
    `create` or `update` methods. All other attributes will be protected from mass assignment. */
    protected $fillable = [
        'from', 'to'
    ];
    protected $table = 'dropshipper_segmentation';
    public $timestamps = true;
    public $searchRelationShip = [];
    public static $rules = [
        
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
        'name' => 'like'
    ];



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

    public function description()
    {
        return $this->morphone(Translation::class, 'category')
            ->where('key', 'description')
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
        return $this->translation()->where('language_id', $lang->id)->where('key','name')->first()->value;
    }

    public function descriptionValue($lang)
    {
        return @$this->translation()->where('language_id', $lang->id)->where('key','description')->first()->value;
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

    public static function getValidationRules()
    {
        return self::$rules;
    }

    /**
     * This function deletes related data when the main data is deleted.
     */
    public static function boot()
    {
        parent::boot();
        static::deleted(function ($data) {
            $data->translation()->delete();
        });
    }


}
