<?php

namespace Modules\MasterCatalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariantValue extends Model
{
    use HasFactory;
    protected $table = 'product_variant_values';

    protected $fillable = ['product_id', 'product_variant_id', 'attribute_option_id', 'attribute_id'];

    public function attributeOption(){
        return $this->belongsTo(AttributeOption::class);
    }

    /**
     * The function "attribute" returns the relationship between the current object and the Attribute
     * class.
     * 
     * return a relationship between the current model and the Attribute model.
     */
    public function attribute(){
        return $this->belongsTo(Attribute::class);
    }


    /**
   * This order function returns the order that belongs to this order_item.
   * 
   * return The order() method returns the order that belongs to the order_id.
   */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

}
