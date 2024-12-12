<?php

namespace Modules\MasterCatalog\Entities;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Modules\Acl\Entities\Dropshipper;

class Profit extends Model
{
    use Loggable;

    /* A white list of attributes that are allowed to be mass assigned. */
    protected $fillable = [
        'profit', 'dropshipper_id', 'product_id', 'is_manual'
    ];

    /* Telling the model to use the profits table. */
    protected $table = 'profits';

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
        'profit' => ['nullable', 'numeric', 'gt:0.00'],
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
     * The function returns the relationship between the Dropshipper model and the User model.
     *
     * return The relationship between the model and the table.
     */
    public function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class);
    }

    /**
     * The function products() returns the relationship between the Product model and the ProductImage
     * model.
     *
     * return The product that belongs to the order.
     */
    public function products()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
