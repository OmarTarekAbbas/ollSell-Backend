<?php

namespace Modules\Logistics\Entities;

use Illuminate\Database\Eloquent\Model;

use Modules\Logistics\Entities\ShippingCompanyCityTime;
use Modules\Logistics\Entities\ShippingCompanyVacation;

class ShippingCompany extends Model
{
    /* '' is an array that specifies which attributes of the model are mass assignable. In
    this case, it allows the 'status', 'order', and 'code' attributes to be set in bulk using the
    'create' or 'update' methods. All other attributes will be protected from mass assignment. */
    protected $fillable = [
         'name', 'phone', 'address', 'email', 'price','weekend', 'loading_unloading', 'grn', 'put_to_shelves', 'qc_process', 'order_fulfillment_start_time', 'order_fulfillment_end_time', 'order_fulfillment', 'returns_management', 'inventory_management', 'vas_activity'
    ];
    protected $table = 'shipping_companies';
    public $timestamps = true;
    public $searchRelationShip = [];


    public $searchConfig = [
        'name' => 'like',
        'phone' => 'like',
        'address' => 'like',
    ];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public static $rules = [
        'phone' => 'required|numeric|unique:shipping_companies',
        'name' => 'required|string|unique:shipping_companies',
        'email' => 'required|string|unique:shipping_companies',
        'address' => 'required|string|unique:shipping_companies',
        'price' => 'required|numeric',
        'order_fulfillment_start_time' => 'required',
        'order_fulfillment_end_time' => 'required|after:order_fulfillment_start_time',

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





    public static function boot()
    {
        parent::boot();
        static::deleting(function ($data) {
            $data->shipping_company_city_time()->delete();
      
        });
    }

    /**
     * This PHP function defines a one-to-many relationship between a model and its related City
     * models.
     * 
     * return A relationship between the current model and the City model is being returned.
     * Specifically, a "has many" relationship, indicating that the current model can have multiple
     * instances of the City model associated with it.
     */
    public function shipping_company_city_time()
    {
        return $this->hasMany(ShippingCompanyCityTime::class);
    }

    public function shipping_company_vacation()
    {
        return $this->hasMany(ShippingCompanyVacation::class);
    }
}
