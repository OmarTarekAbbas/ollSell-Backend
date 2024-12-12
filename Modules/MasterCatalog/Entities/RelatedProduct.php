<?php

namespace Modules\MasterCatalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RelatedProduct extends Model
{
    use HasFactory;

    protected $table = 'related_products';

    protected $fillable = ['related_product_id', 'product_id'];

    function related_product()
    {
        return $this->belongsTo(Product::class,'related_product_id');
    }
}
