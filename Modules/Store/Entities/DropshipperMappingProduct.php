<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\MasterCatalog\Entities\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DropshipperMappingProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'dropshipper_mapping_products';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
