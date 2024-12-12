<?php

namespace Modules\Store\Entities;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DropshipperMappingOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'dropshipper_mapping_orders';


}
