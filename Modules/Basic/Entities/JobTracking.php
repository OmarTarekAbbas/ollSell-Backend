<?php

namespace Modules\Basic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobTracking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'token','type'];

    protected static function newFactory()
    {
        return \Modules\Basic\Database\factories\JobTrackingFactory::new();
    }
}
