<?php

namespace Modules\CoreData\Entities;

use Illuminate\Database\Query\Builder;
use Modules\Basic\Entities\Media;
use Illuminate\Database\Eloquent\Model;
use Modules\Basic\Entities\Translation;
use Modules\MasterCatalog\Entities\Product;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CoreData\Database\Factories\CategoryFactory;

class Category extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'status', 'parent_id', 'commission', 'supplier_id', 'isApproved', 'reason'
    ];
    protected $table = 'categories';
    public $timestamps = true;

    public static $rules = [
        'commission' => 'nullable|numeric|min:0.00|max:1000.00',
    ];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [
        'name' => 'like',
    ];
    public $searchRelationShip = [];
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['name', 'metaTitle', 'metaDescription'];

    /**
     * The function returns a new instance of the CategoryFactory class.
     *
     * return The method is returning an instance of the CategoryFactory class.
     */
    protected static function newFactory()
    {
        return CategoryFactory::new();
    }

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
     * The function returns an array of translation keys for name, metaTitle, and metaDescription.
     *
     * return An array containing the keys 'name', 'metaTitle', and 'metaDescription'.
     */
    public static function translationKey()
    {
        return ['name', 'metaTitle', 'metaDescription'];
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
     * This PHP function defines a one-to-many relationship between a model and its related products.
     *
     * return A relationship between the current model and the `Product` model is being returned.
     * Specifically, a one-to-many relationship where the current model has many `Product` instances.
     */
    public function product()
    {
        return $this->hasMany(Product::class);
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
            $data->media()->delete();

            // Set the parent_id to null for child categories
            $data->children->each(function ($childCategory) {
                $childCategory->update(['parent_id' => null]);
            });
        });
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
     * This PHP function returns the avatar media of an object.
     *
     * return The `avatar()` function is returning a query builder instance that filters the media
     * associated with the current model instance to only include those with a type of
     * `mediaType()['am']`.
     */
    public function avatar()
    {
        return $this->media()->whereType(mediaType()['am']);
    }

    /**
     * This PHP function returns the meta title for a category in a specific language.
     *
     * return The function `metaTitle()` is returning a query builder instance that selects the
     * `metaTitle` value from the `translations` table for the current category and language.
     */
    public function metaTitle()
    {
        return $this->morphone(Translation::class, 'category')
            ->where('key', 'metaTitle')
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
    public function metaTitleValue($lang)
    {
        return $this->translation()->where('key', 'metaTitle')->where('language_id', $lang->id)->first()->value ?? "";
    }

    /**
     * This PHP function returns the meta title for a category in a specific language.
     *
     * return The function `metaDescription()` is returning a query builder instance that selects the
     * `metaDescription` value from the `translations` table for the current category and language.
     */
    public function metaDescription()
    {
        return $this->morphone(Translation::class, 'category')
            ->where('key', 'metaDescription')
            ->where('language_id', languageId());
    }

    /**
     * This PHP function returns the value of the meta title translation for a given language.
     *
     * param lang  is a variable that represents the language for which the meta title value is
     * being retrieved. It is likely an object that contains information about the language, such as
     * its ID or name.
     *
     * return the value of the "metaDescriptionValue" translation key for a specific language, as stored in the
     * database.
     */
    public function metaDescriptionValue($lang)
    {
        return $this->translation()->where('key', 'metaDescription')->where('language_id', $lang->id)->first()->value ?? "";
    }

    /**
     * This PHP function returns a collection of MetaCategory objects associated with a parent object.
     *
     * return A relationship between the current model and the MetaCategory model is being returned.
     * Specifically, a "hasMany" relationship is being established, indicating that the current model
     * can have multiple instances of the MetaCategory model associated with it.
     */
    public function metaCategory()
    {
        return $this->hasMany(MetaCategory::class);
    }

    /**
     * The function returns the parent category of the current category.
     *
     * return a relationship between the current model and the Category model. The relationship is
     * defined by the "belongsTo" method, which indicates that the current model belongs to a Category
     * model. The second argument of the "belongsTo" method specifies the foreign key column name in
     * the current model's table, which is "parent_id".
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * The function returns a collection of child categories for a given parent category.
     *
     * return a collection of Category objects that have a parent_id matching the current object's id.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * The function "products" defines a many-to-many relationship between the current class and the
     * "Product" class in PHP.
     *
     * return a many-to-many relationship between the current model and the Product model.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id');
    }
    public function withProducts()
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id')
            ->where('products.status',1)->where('products.isApproved',1)
            ->where(function($query)
            {
                $query->whereHas('product_dropshippers', function ( $query) {
                $query->where('product_dropshippers.dropshipper_id',  user()->id);
            })
                ->orWhereDoesntHave('product_dropshippers', function ( $query) {
                    $query->where('product_dropshippers.dropshipper_id', '!=', user()->id);
                });
            })
            ->limit(4);
    }
    public function similarCategories()
    {
        $currentCategory = $this;
        $translationKeys = Category::translationKey();

        $similarCategories = Category::where('isApproved', 1)->where(function ($query) use ($currentCategory, $translationKeys) {
            foreach ($translationKeys as $key) {
                $currentTranslation = $currentCategory->translation($key)->first();
                if ($currentTranslation) {
                    // Get the value from the current translation
                    $enteredNameWords = explode(' ', strtolower($currentTranslation->value));
                    foreach ($enteredNameWords as $word) {
                        // Check for similar categories based on each word in the translation value
                        $query->orWhereHas('translation', function ($query) use ($currentTranslation, $word) {
                            $query->where('key', $currentTranslation->key)
                                ->whereRaw('LOWER(value) LIKE ?', ['%' . strtolower($word) . '%']);
                        });
                    }
                }
            }
        })
        ->where('id', '!=', $currentCategory->id)
        ->get();

        return $similarCategories;
    }


}
