<?php

namespace Modules\Acl\Entities;

use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Modules\Supplier\Entities\Warehouse;
use Modules\CoreData\Entities\Notification;
use Modules\MasterCatalog\Entities\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Supplier extends Authenticatable
{
    use HasFactory, Notifiable;

    public static array $rules = [
        'name' => 'required|unique:suppliers',
        'email' => 'required|email|unique:suppliers',
    ];

    public static function getValidationRules(): array
    {
        return self::$rules;
    }
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    protected $fillable = ['name', 'email', 'password'];
    /**
     * The function returns an array containing the translation keys for "name" and "description".
     *
     * return An array containing the strings "name" and "description".
     */
    public static function translationKey()
    {
        return [];
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'user');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($user) {
            $products = Product::where('supplier_id', $user->id)->get();
            $warehouses = Warehouse::where('supplier_id', $user->id)->get();

            foreach($products as $product) {
                $product->delete();
            }

            foreach($warehouses as $warehouse) {
                $products = Product::where('warehouse_id', $warehouse->id)->get();

                foreach($products as $product) {
                    $product->delete();
                }

                $warehouse->delete();
            }
        });
    }
}
