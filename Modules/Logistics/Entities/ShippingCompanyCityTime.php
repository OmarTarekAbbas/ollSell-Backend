<?php

namespace Modules\Logistics\Entities;


use Illuminate\Database\Eloquent\Model;
use Modules\Logistics\Entities\ShippingCompany;
use Modules\CoreData\Entities\City;
class ShippingCompanyCityTime extends Model
{


    /* The 'protected ' property is used in Laravel's Eloquent ORM to specify which attributes
    of a model are fillable. In this case, the 'City' model can have its 'status' and 'country_id'
    attributes filled with data from a form or other input source. Any other attributes not listed
    in '' will be guarded and cannot be mass assigned. This is a security feature to
    prevent unintended data manipulation. */
    protected $fillable = [
        'price', 'number_hours', 'city_id', 'shipping_company_id'
    ];
    protected $table = 'shipping_company_city_times';
    public $timestamps = true;
    public $searchRelationShip = [];


    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [
        'number_hours' => 'like',
    ];

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public static $rules = [
        'shipping_company_id' => 'required|exists:shipping_companies,id',
        'city_id' => 'required|exists:cities,id',
        'number_hours' => 'required',
        'price' => 'required|numeric'
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
     * This is a PHP function that defines a relationship between the current model and the Country
     * model.
     *
     * return A relationship between the current model and the 'Country' model is being returned.
     * Specifically, it is a 'belongsTo' relationship, indicating that the current model belongs to a
     * single instance of the 'Country' model.
     */
    public function shipping_company()
    {
        return $this->belongsTo(ShippingCompany::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }


}
