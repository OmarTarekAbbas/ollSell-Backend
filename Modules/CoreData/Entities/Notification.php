<?php

namespace Modules\CoreData\Entities;

use Modules\Basic\Entities\Translation;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * Order statuses list
     *
     * @const string
     */
    const ADMIN = 'App\Models\User';
    const DROPSHIPPER = 'Modules\Acl\Entities\Dropshipper';
    const SUPPLIER = 'Modules\Acl\Entities\Supplier';

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'user_type',
        'seen',
        'seenAt',
    ];
    protected $table = 'notifications';
    public $timestamps = true;
    public static $rules = [];

    protected $casts = [
        'seenAt' => 'datetime'
    ];

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [
        'title' => 'like',
    ];
    public $searchRelationShip = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [];

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
     * This function deletes the translation and media associated with a data object when it is
     * deleted.
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($data) {
            $data->translation()->delete();
        });
    }

    /**
     * The function returns an array of translation keys for name, metaTitle, and metaDescription.
     *
     * return An array containing the keys 'name', 'metaTitle', and 'metaDescription'.
     */
    public static function translationKey()
    {
        return ['title', 'content'];
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
     * This PHP function returns the meta title for a category in a specific language.
     *
     * return The function `metaTitle()` is returning a query builder instance that selects the
     * `metaTitle` value from the `translations` table for the current category and language.
     */
    public function title()
    {
        return $this->morphone(Translation::class, 'category')
            ->where('key', 'title')
            ->where('language_id', languageId());
    }

    /**
     * This PHP function returns the value of the meta title translation for a given language.
     *
     * param lang  is a variable that represents the language for which the meta title value is
     * being retrieved. It is likely an object that contains information about the language, such as
     * its ID or name.
     *
     * return the value of the "metaTitle" translation key for a specific language, as stored in the
     * database.
     */
    public function titleValue($lang)
    {
        return $this->translation()->where('key', 'title')->where('language_id', $lang->id)->first()->value ?? "";
    }

    /**
     * This PHP function returns the meta title for a category in a specific language.
     *
     * return The function `metaDescription()` is returning a query builder instance that selects the
     * `metaDescription` value from the `translations` table for the current category and language.
     */
    public function content()
    {
        return $this->morphone(Translation::class, 'category')
            ->where('key', 'content')
            ->where('language_id', languageId());
    }

    /**
     * This PHP function returns the value of the meta title translation for a given language.
     *
     * param lang  is a variable that represents the language for which the meta title value is
     * being retrieved. It is likely an object that contains information about the language, such as
     * its ID or name.
     *
     * return the value of the "metaTitle" translation key for a specific language, as stored in the
     * database.
     */
    public function contentValue($lang)
    {
        return $this->translation()->where('key', 'content')->where('language_id', $lang->id)->first()->value ?? "";
    }

    /**
     * The function "user" returns the polymorphic relationship for the current object.
     *
     * return the result of the `morphTo()` method.
     */
    public function user()
    {
        return $this->morphTo();
    }
}
