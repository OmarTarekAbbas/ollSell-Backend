<?php

namespace Modules\MasterCatalog\Entities;

use Modules\Basic\Entities\Media;
use Modules\Acl\Entities\Dropshipper;
use Modules\Order\Entities\Cart;
use Modules\Order\Entities\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Modules\CoreData\Entities\Category;
use Modules\Basic\Entities\Translation;
use Illuminate\Database\Eloquent\Builder;
use Modules\CoreData\Entities\TargetMarket;
use Illuminate\Database\Eloquent\SoftDeletes;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Modules\Order\Enums\OrderEnum;
use Modules\Store\Entities\DropshipperMappingProduct;

class Product extends Model
{
    use SoftDeletes, Loggable;

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    protected $fillable = [
        'status', 'cost_price', 'sku', 'size', 'is_discount', 'saleCountProduct', 'is_recommended', 'priceAfterDiscount',
        'quantity', 'weight', 'supplier_id', 'warehouse_id', 'isApproved', 'commission', 'vat_commission', 'supplier_price_cost',
        'barcode', 'attributes_data', 'variants_data', 'slug', 'meta_title', 'product_meta_description', 'product_meta_keywords',
        'custam_commission','is_wms'
    ];
    protected $table = 'products';
    public $timestamps = true;
    public $searchRelationShip = [
        'target_market' => 'productTargetMarket->target_market_id',
        'category' => 'categories->category_id',
        'category_id' => 'categories->category_id',
    ];
    /**
     * @inheritdoc
     */
    protected $dates = ['created_at', 'deleted_at'];
    protected $casts = [
        'attributes_data' => 'array'
    ];

    /**
     * Configure the Model
     **/
    public function modelFavorite()
    {
        return Favorite::class;
    }

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
        'cost_price' => 'required|numeric|min:1',
        'commission' => 'nullable|numeric|min:0',
        'sku' => 'required|min:4',
        'weight' => 'required|numeric|gt:0',
        'status' => 'required',
        'description' => 'required',
        'warehouse_id' => 'required',
        'final_cost_price' => 'nullable',
        'barcode' => 'nullable|min:5|max:50|regex:/^[A-Za-z0-9-]+$/',
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
     * This PHP function defines a relationship between the current model and the Category model.
     *
     * return A relationship between the current model and the Category model is being returned.
     * Specifically, a "belongsTo" relationship is being defined, indicating that the current model
     * belongs to a single instance of the Category model.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
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
        return $this->translation()->where('key', 'name')->where('language_id', $lang)->first()->value;
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
        return $this->translation()->where('key', 'description')->where('language_id', $lang)->first()->value;
    }

    /**
     * This PHP function returns a polymorphic relationship between the current object and the Media
     * model.
     *
     * return The `media()` function is returning a morphMany relationship between the current model
     * and the `Media` model, where the `category` column of the `Media` table is used to store the
     * type of the related model.
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'category');
    }

    /**
     * The function returns the logo media of an object.
     *
     * return The `logo()` function is returning a query builder instance that retrieves the media
     * associated with the current object (likely a model) where the media type is equal to the value
     * of the `lm` key in the `mediaType()` array.
     */
    public function logo()
    {
        return $this->media()->whereType(mediaType()['lm']);
    }

    /**
     * The function returns the thumbnail media of an object.
     *
     * return The `thumbnail()` function is returning a query builder instance that retrieves the media
     * associated with the current object (likely a model) where the media type is equal to the value
     * of the `lm` key in the `mediaType()` array.
     */
    public function thumbnail()
    {
        return $this->media()->whereType(mediaType()['th']);
    }

    /**
     * This PHP function defines a many-to-many relationship between a product and its target markets.
     *
     * return A many-to-many relationship between the current model and the TargetMarket model, using
     * the "product_target_markets" table as the intermediate table.
     */
    public function targetMarket()
    {
        return $this->belongsToMany(TargetMarket::class, 'product_target_markets');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function productVariantValues()
    {
        return $this->hasMany(ProductVariantValue::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class,'event_product', 'product_id', 'event_id');
    }
    /**
     * This PHP function returns a collection of ProductTargetMarket objects associated with a specific
     * product.
     *
     * return A relationship between the current model and the ProductTargetMarket model is being
     * returned. Specifically, a "hasMany" relationship is being established, indicating that the
     * current model can have multiple instances of the ProductTargetMarket model associated with it.
     */
    public function productTargetMarket()
    {
        return $this->hasMany(ProductTargetMarket::class);
    }

    /**
     * A user can have many dropshippers, and a dropshipper can have many users.
     *
     * @return The relationship between the user and the dropshipper.
     */
    public function dropshipper()
    {
        return $this->belongsToMany(Dropshipper::class, 'favorites');
    }

    /**
     * This product has many favorites.
     *
     * @return The hasMany relationship is being returned.
     */
    public function favorite()
    {
        return $this->belongsTo(Favorite::class);
    }

    /**
     * This function deletes the translation and media associated with a data object when it is
     * deleted.
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function($data)
        {
            if(auth()->guard('supplier')->check())
            {
                $data->supplier_id = auth()->guard('supplier')->user()->id;
            }
        });
        static::addGlobalScope('supplier_id', function(Builder $builder)
        {
            if(auth()->guard('supplier')->check())
            {
                $builder->where('supplier_id', '=', auth()->guard('supplier')->user()->id);
            }
        });
    }

    /**
     * It calculates the price of a product based on the total profit of the user
     *
     * param model The model that you want to calculate the price for.
     *
     * @return float The return value is the price of the product.
     */
    public function calculator($dropshipperId = null): float
    {
        // Determine the base price
        $basePrice = $this->cost_price;
        return round(($basePrice), 2);
    }

    /**
     * It returns the profit of a product
     *
     * @return float The profit of the product.
     */
    public function profitProduct()
    {
        $profitProduct = $this->queryProfitProduct();
        if($profitProduct)
        {
            return $profitProduct->profit;
        }
        return user()->profit;
    }

    /**
     * The profitAmount function calculates the profit amount by subtracting the cost price from the
     * calculated price and rounding the result to two decimal places.
     *
     * @return the profit amount, which is calculated by subtracting the cost price from the result of
     * the calculator function. The result is rounded to 2 decimal places.
     */
    public function profitAmount()
    {
        $basePrice = $this->is_discount ? $this->priceAfterDiscount : $this->cost_price;
        return round(($basePrice * ($this->profitProduct() / 100)), 2);
    }

    /**
     * The function calculates the VAT (Value Added Tax) for a product based on its cost price and
     * profit amount.
     *
     * @return the value of the VAT (Value Added Tax) for the product.
     */
    public function vatProduct()
    {
        $basePrice = $this->is_discount ? $this->priceAfterDiscount : $this->cost_price;
        return round(($basePrice * setting('shipping_fee')) + ($this->profitAmount() * setting('shipping_fee')), 2);
    }

    /**
     * It returns the latest profit of the product
     *
     * @return the latest profit of the product.
     */
    public function queryProfitProduct()
    {//todo change
        return Profit::where('dropshipper_id', user()->id)->where('product_id', $this->id)->latest()->first();
    }

    /**
     * It returns the dropshipper mapping  of the product
     *
     * @return the latest profit of the product.
     */
    public function queryMappingProduct()
    {
        return DropshipperMappingProduct::where('dropshipper_id', user()->id)->where('product_id', $this->id)->latest()
            ->first();
    }

    /**
     * If the product is selected, return true, else return false
     *
     * @return bool A boolean value.
     */
    public function isFavorite(): bool
    {
        $isFavorite = $this->modelFavorite()::where('product_id', $this->id)->where('dropshipper_id', user()->id)
            ->first();
        if($isFavorite)
        {
            return true;
        }
        return false;
    }
    public function isCart(): bool
    {
        $cart = Cart::where('product_id', $this->id)->where('dropshipper_id', user()->id)
            ->first();
        if($cart)
        {
            return true;
        }
        return false;
    }

    /**
     * It returns the logo of the company if it exists, otherwise it returns a blank avatar.
     *
     * @return The logoExcel() method is returning the logo image.
     */
    public function logoExcel()
    {
        return url('images/product/' . $this->category_id . '/' . $this->file);
    }

    /**
     * This function returns a relationship between the current model and the Profit model.
     *
     * @return The relationship between the two models.
     */
    public function profits()
    {
        return $this->hasMany(Profit::class);
    }

    /**
     * It returns the latest profit of the product.
     *
     * @return The latest profit of the product.
     */
    public function isManual()
    {
        $profitProduct = $this->queryProfitProduct();
        if($profitProduct && $profitProduct->is_manual == 1)
        {
            return true;
        }
        return false;
    }

    /**
     * This function returns a collection of OrderItem objects that have a product_id that matches the
     * id of the Product object that this function is called on.
     *
     * @return The orderItems() method returns a collection of OrderItem objects.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'id');
    }
    public function capasteQuantity($status = OrderEnum::PENDING_INVENTORY_STATUS)
    {
        return $this->orderItems()->where('status_id',$status)->sum('quantity');
    }
    public function capasteOrder($status = OrderEnum::PENDING_INVENTORY_STATUS)
    {
        return $this->orderItems()->where('status_id',$status)->count();
    }
    /**
     * The function "categories" defines a many-to-many relationship between the current class and the
     * "Category" class in PHP.
     *
     * @return a many-to-many relationship between the current model and the Category model.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

    /**
     * The function returns the translation for the key 'name'.
     *
     * @return a query builder instance for the translation table where the key column is equal to
     * 'name'.
     */
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

    /**
     * This PHP function calculates the selling price of a product based on its base price, profit
     * percentage, and VAT.
     *
     * param dropshipperId The dropshipperId parameter is the ID of the dropshipper for whom the
     * calculation is being done.
     *
     * @return float a float value, which is the calculated selling price of a product after applying
     * the profit percentage and adding the VAT (Value Added Tax) to it.
     */
    public function calculatorCleaningDBOrder($dropshipperId): float
    {
        $profitProduct = Profit::where('dropshipper_id', $dropshipperId)->where('product_id', $this->id)->latest()
            ->first();;
        // Determine the base price
        $basePrice = $this->is_discount ? $this->priceAfterDiscount : $this->cost_price;
        // Determine the profit percentage
        $generalProfit = user()->profit ?? 0;
        $customProfit = $profitProduct && $profitProduct->is_manual == 1 ? $profitProduct->profit : null;
        $profitPercentage = $customProfit !== null ? $customProfit : $generalProfit;
        // Calculate the selling price
        $sellingPrice = $basePrice + ($basePrice * ($profitPercentage / 100));
        if(!$this->is_discount)
        {
            // If it's not a discounted product, apply the profit margin directly to the cost_price
            $sellingPrice = $this->cost_price + ($this->cost_price * ($profitPercentage / 100));
        }
        // get tax and add it to selling price
        // $tax = ($sellingPrice + $basePrice) * setting('shipping_fee');
        return round(($sellingPrice + $this->vatProductCleaningDBOrder($dropshipperId)), 2);
    }

    /**
     * The function calculates the VAT (Value Added Tax) for a product based on its cost price and
     * profit amount.
     *
     * @return the value of the VAT (Value Added Tax) for the product.
     */
    public function vatProductCleaningDBOrder($dropshipperId)
    {
        $basePrice = $this->is_discount ? $this->priceAfterDiscount : $this->cost_price;
        return round(($basePrice * setting('shipping_fee')) + ($this->profitAmountCleaningDBOrder($dropshipperId) * setting('shipping_fee')), 2);
    }

    public function profitAmountCleaningDBOrder($dropshipperId)
    {
        $basePrice = $this->is_discount ? $this->priceAfterDiscount : $this->cost_price;
        return round(($basePrice * ($this->profitProductCleaningDBOrder($dropshipperId) / 100)), 2);
    }

    public function profitProductCleaningDBOrder($dropshipperId)
    {
        $profitProduct = $this->queryProfitProductCleaningDBOrder($dropshipperId);
        if($profitProduct)
        {
            return $profitProduct->profit;
        }
        $dropshipper = Dropshipper::find($dropshipperId);
        return $dropshipper->profit;
    }

    public function queryProfitProductCleaningDBOrder($dropshipperId)
    {
        return Profit::where('dropshipper_id', $dropshipperId)->where('product_id', $this->id)->latest()->first();
    }
    public function related_product()
    {
        return $this->belongsToMany(RelatedProduct::class, 'related_products','product_id','related_product_id');
    }
    public function related_products()
    {
        return $this->hasMany(RelatedProduct::class, 'product_id');
    }

    public function related()
    {
        return $this->belongsToMany(RelatedProduct::class, 'related_products');
    }

    public function ProductLog()
    {
        return $this->HasMany(productLog::class, 'product_id');
    }

    public function product_dropshipper()
    {
        return $this->belongsToMany(ProductDropshipper::class, 'product_dropshippers','product_id','dropshipper_id');
    }
    public function product_dropshippers()
    {
        return $this->hasMany(ProductDropshipper::class, 'product_id');
    }

    public function dropshippers()
    {
        return $this->belongsToMany(ProductDropshipper::class, 'dropshipper_id');
    }

    public function bundle()
    {
        return $this->HasMany(BundleProduct::class);
    }
}
