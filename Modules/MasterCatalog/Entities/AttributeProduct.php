<?php

namespace Modules\MasterCatalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttributeProduct extends Model
{
    use HasFactory;

    protected $table = 'attribute_product';

    protected $fillable = ['attribute_id', 'product_id'];

}
