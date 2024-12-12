<?php

namespace Modules\MasterCatalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Acl\Entities\Dropshipper;

class ProductDropshipper extends Model
{
    use HasFactory;

    protected $table = 'product_dropshippers';

    protected $fillable = ['dropshipper_id','product_id'];

    function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class,'dropshipper_id');
    }
}
