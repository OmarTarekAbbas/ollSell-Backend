<?php

namespace Modules\MasterCatalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryProduct extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'product_id'];
    protected $table = 'category_product';
    public $timestamps = true;

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
