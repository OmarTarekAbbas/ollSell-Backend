<?php

namespace Modules\MasterCatalog\Entities;


use Illuminate\Database\Eloquent\Model;
use Modules\Basic\Entities\Translation;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Modules\Order\Entities\Cart;

class Bundle extends Model
{
    use Loggable;

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    protected $fillable = [
        'sku', 'quantity','discount', 'cost_price', 'status'
    ];
    protected $table = 'bundles';
    public $timestamps = true;
    public $searchRelationShip = [

    ];
    /**
     * @inheritdoc
     */
    protected $dates = ['created_at', 'deleted_at'];
    protected $casts = [
        'attributes_data' => 'array'
    ];



    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['name', 'description'];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [
        'name' => 'like',
        'description' => 'like',
    ];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */ 
    public static $rules = [
         'cost_price' => 'required|numeric',
         'discount' => 'required',
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
     * The function returns an array containing the translation keys for "name" and "description".
     *
     * return An array containing the strings "name" and "description".
     */
    public static function translationKey()
    {
        return ['name', 'description'];
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
     * This PHP function returns the value of the "name" translation key for a given language.
     *
     * param lang  is a variable that represents the language for which the name value is being
     * retrieved. It is likely an object that contains information about the language, such as its ID.
     *
     * return the value of the translation for the key 'name' in the specified language ().
     */
    public function nameValue($lang)
    {
 
        return @$this->translation()->where('key', 'name')->where('language_id', $lang->id)->first()->value;
    }

    /**
     * This PHP function returns the description of a category in a specific language.
     *
     * return a query builder instance that selects the "description" translation for the current
     * language and category.
     */
    public function description()
    {
        return $this->morphone(Translation::class, 'category')
            ->where('key', 'description')
            ->where('language_id', languageId());
    }

    /**
     * This PHP function returns the value of the "description" translation for a given language.
     *
     * param lang  is a variable that represents the language for which the description value is
     * being retrieved. It is likely an object that contains information about the language, such as
     * its ID or name.
     *
     * return the value of the "description" translation for a specific language, which is retrieved
     * from the database using the "translation" relationship of the current object and filtered by the
     * language ID and translation key.
     */
    public function descriptionValue($lang)
    {
        return @$this->translation()->where('key', 'description')->where('language_id', $lang->id)->first()->value;
    }
    public function getTotalTranslation()
    {
        return $this->translation()->where('key', 'name');
    }

    /**
     * The function returns the translation for the key 'description'.
     *
     * @return a query builder instance for the translation table where the key column is equal to
     * 'description'.
     */
    public function getTotalTranslationDescription()
    {
        return $this->translation()->where('key', 'description');
    }


    public function products()
    {
        return $this->hasMany(BundleProduct::class, 'bundle_id');
    }

    public function bundle_product()
    {
        return $this->belongsToMany(BundleProduct::class, 'bundle_products','bundle_id','product_id');
    }

    public function bundle_dropshipper()
    {
        return $this->belongsToMany(BundleDropshipper::class, 'bundle_dropshippers','bundle_id','dropshipper_id');
    }
    public function bundle_dropshippers()
    {
        return $this->hasMany(BundleDropshipper::class, 'bundle_id');
    }

    public function dropshippers()
    {
        return $this->belongsToMany(BundleDropshipper::class, 'dropshipper_id');
    }

    public function isCart(): bool
    {
        $cart = Cart::where('bundle_id', $this->id)->where('dropshipper_id', user()->id)
            ->first();
        if($cart)
        {
            return true;
        }
        return false;
    }

}
