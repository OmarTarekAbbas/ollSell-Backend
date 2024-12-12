<?php

namespace Modules\MasterCatalog\Entities;

use Modules\Acl\Entities\Dropshipper;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Favorite extends Model
{
    use Loggable;

    /* A white list of attributes that are allowed to be mass assigned. */
    protected $fillable = ['dropshipper_id', 'product_id'];

    /* Telling the model to use the profits table. */
    protected $table = 'favorites';

    /* Telling the model to use the timestamps created_at and updated_at. */
    public $timestamps = true;

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];
    public $searchRelationShip = [];
    public static $rules = [
        'products' => 'required|array',
        'products.*' => 'required|exists:products,id',
    ];

    /**
     * This function returns the validation rules for the model.
     *
     * return The rules for the model.
     */
    public static function getValidationRules()
    {
        return self::$rules;
    }

    /**
     * This function returns an array of all the keys that are used in the translation files.
     *
     * return An array of the keys that are to be translated.
     */
    public static function translationKey()
    {
        return [];
    }

    /**
     * This function returns a HasOne relationship between the current model and the Product model.
     *
     * return HasOne A HasOne relationship
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * The `dropShipper()` function returns a `BelongsTo` relationship between the `Order` model and
     * the `Dropshipper` model
     *
     * return BelongsTo A relationship between the model and the dropshipper.
     */
    public function dropShipper()
    {
        return $this->belongsTo(Dropshipper::class);
    }
}
