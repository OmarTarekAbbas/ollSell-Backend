<?php

namespace Modules\MasterCatalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'price', 'quantity', 'sku', 'weight', 'commission', 'cost_price', 'vat', 'commission_vat'];

    public function productVariantValues()
    {
        return $this->hasMany(ProductVariantValue::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($data) {
            $data->media()->delete();
        });
    }
}
