<?php

namespace Modules\Basic\Entities;

use Illuminate\Database\Eloquent\Model;

class Seeder extends Model
{
    public $table = 'seeders';
    public $fillable = [
        'seeder',
    ];
    public $searchRelationShip  = [];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'seeder' => 'string'
    ];
}
