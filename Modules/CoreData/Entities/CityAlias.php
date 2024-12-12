<?php

namespace Modules\CoreData\Entities;

use Illuminate\Database\Eloquent\Model;

class CityAlias extends Model
{
    protected $fillable = [
        'alias', 'city_id'
    ];
    protected $table = 'city_aliases';
    public $timestamps = true;
    public static $rules = [];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [
        'alias' => 'like',
    ];
    public $searchRelationShip = [];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
