<?php

namespace Modules\Supplier\Entities;

use Modules\Acl\Entities\Supplier;
use Modules\CoreData\Entities\City;
use Illuminate\Support\Facades\Auth;
use Modules\CoreData\Entities\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\MasterCatalog\Entities\Product;
//todo change
class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    protected $fillable = ['name', 'address', 'country_id', 'city_id', 'supplier_id', 'district', 'location', 'is_internal','is_admin'];

    public static array $rules = [
        'name' => 'required|max:255|unique:warehouses,name',
        'country_id' => 'required|numeric',
        'city_id' => 'required|numeric',
        'address' => 'required|max:255',
        'district' => 'required|max:50',
        'location' => 'nullable|url',
    ];
    public static function getValidationRules(): array
    {
        return self::$rules;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($warehouse) {
            if (Auth::guard('supplier')->check() && !$warehouse->supplier_id) {
                $warehouse->supplier_id = Auth::guard('supplier')->user()->id;
            }
        });

        static::addGlobalScope('supplier_id', function (Builder $builder) {
            if (auth()->guard('supplier')->check()) {
                $builder->where('supplier_id', '=', auth()->guard('supplier')->user()->id)->orWhere('is_internal', 1)->orWhere('is_admin', 1);
            }
        });
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }


    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
