<?php

namespace Modules\CoreData\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Acl\Entities\Dropshipper;
use Modules\Acl\Entities\DropshipperTargetMarket;
use Modules\Basic\Entities\Translation;
use Modules\MasterCatalog\Entities\Product;
use Modules\MasterCatalog\Entities\ProductTargetMarket;

class TargetMarket extends Model
{
    protected $fillable = [
        'status', 'order', 'code'
    ];
    protected $table = 'target_markets';
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
    ];

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public static $rules = [
        'order' => 'required|numeric|unique:target_markets',
        'code' => 'required|string|unique:target_markets',
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

    /**
     * This PHP function defines a many-to-many relationship between the current model and the
     * Dropshipper model through the "dropshipper_target_markets" table.
     *
     * return A many-to-many relationship between the current model and the `Dropshipper` model, using
     * the `dropshipper_target_markets` table as the intermediate table.
     */
    public function dropshipper()
    {
        return $this->belongsToMany(Dropshipper::class, 'dropshipper_target_markets');
    }

    /**
     * This PHP function returns a collection of DropshipperTargetMarket objects associated with a
     * specific instance.
     *
     * return A hasMany relationship between the current model and the DropshipperTargetMarket model
     * is being returned.
     */
    public function dropshipperTargetMarket()
    {
        return $this->hasMany(DropshipperTargetMarket::class);
    }

    /**
     * This is a PHP function that defines a many-to-many relationship between a product and its target
     * markets.
     *
     * return A many-to-many relationship between the current model (assumed to be a Product model)
     * and the Product model, using the pivot table 'product_target_markets'.
     */
    public function Product()
    {
        return $this->belongsToMany(Product::class, 'product_target_markets');
    }

    /**
     * This PHP function returns a collection of ProductTargetMarket objects associated with a specific
     * product.
     *
     * return A relationship between the current model and the `ProductTargetMarket` model is being
     * returned. Specifically, a one-to-many relationship where a product can have multiple target
     * markets.
     */
    public function ProductTargetMarket()
    {
        return $this->hasMany(ProductTargetMarket::class);
    }

    /**
     * This function deletes related data when a model is deleted.
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($data) {
            $data->translation()->delete();
            $data->dropshipperTargetMarket()->delete();
            $data->ProductTargetMarket()->delete();
        });
    }
}
