<?php

namespace Modules\MasterCatalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Acl\Entities\Dropshipper;

class BundleDropshipper extends Model
{
    use HasFactory;

    protected $table = 'bundle_dropshippers';

    protected $fillable = ['dropshipper_id','bundle_id'];

    function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class,'dropshipper_id');
    }
}
