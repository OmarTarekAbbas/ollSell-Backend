<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Modules\Acl\Entities\Dropshipper;
use Modules\MasterCatalog\Entities\Product;
use Modules\MasterCatalog\Entities\Bundle;
use Illuminate\Support\Facades\Validator;

class Cart extends Model
{
    use Loggable;

    /* A white list of attributes that are allowed to be mass assigned. */
    protected $fillable = [
        'dropshipper_id', 'product_id', 'bundle_id', 'quantity', 'selling_price' // Add bundle_id here
    ];

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];
    public $searchRelationShip = [];
    
    public static $rules = [
        'product_id' => 'nullable|exists:products,id',
        'bundle_id' => 'nullable|exists:bundles,id',
        'selling_price' => 'required|not_in:0',
        'quantity' => 'required|integer|min:1',
    ];

    /**
     * Custom validation rule to ensure either product_id or bundle_id is present.
     */
    public static function validate($data)
    {
        $validator = Validator::make($data, self::$rules);

        $validator->after(function ($validator) use ($data) {
            if (empty($data['product_id']) && empty($data['bundle_id'])) {
                $validator->errors()->add('product_id', 'Either product_id or bundle_id must be present.');
            }
        });

        return $validator;
    }

    /**
     * This function returns the validation rules for the model.
     *
     * @return The rules for the model.
     */
    public static function getValidationRules()
    {
        return self::$rules;
    }

    /**
     * This function returns an array of all the keys that are used in the translation files.
     *
     * @return An array of the keys that are to be translated.
     */
    public static function translationKey()
    {
        return [];
    }

    public function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class, 'dropshipper_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'bundle_id');
    }
}
