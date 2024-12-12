<?php

namespace Modules\Store\Entities;

use Modules\Acl\Entities\Dropshipper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DropshipperEcommerce extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'dropshipper_ecommerces';

    public function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class);
    }

}
