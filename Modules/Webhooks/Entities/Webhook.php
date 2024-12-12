<?php

namespace Modules\Webhooks\Entities;

use Modules\Acl\Entities\Dropshipper;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    protected $fillable = ['dropshipper_id', 'event', 'url'];

    public function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class);
    }
}
