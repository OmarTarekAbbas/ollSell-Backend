<?php

namespace Modules\MasterCatalog\Entities;


use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class BundleProduct extends Model
{
    use  Loggable;

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    protected $fillable = [ 'product_id', 'bundle_id', 'count'];
    protected $table = 'bundle_products';
    public $timestamps = true;
    public $searchRelationShip = [

    ];
    /**
     * @inheritdoc
     */
    protected $dates = ['created_at'];
    protected $casts = [
        'attributes_data' => 'array'
    ];




    public $searchConfig = [
    
    ];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public static $rules = [
        'product_id' => 'required',
        'bundle_id' => 'required',
        'count' => 'required|numeric|min:1',
   
    ];

    /**
     * This function returns the validation rules.
     *
     * return The 'getValidationRules()' function is returning the static property ''.
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
     * This function returns a HasOne relationship between the current model and the Product model.
     *
     * return HasOne A HasOne relationship
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }



    public function bundle_product()
    {
        return $this->belongsToMany(BundleProduct::class, 'bundle_products','product_id','bundle_id');
    }

    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'bundle_id');
    }

}
