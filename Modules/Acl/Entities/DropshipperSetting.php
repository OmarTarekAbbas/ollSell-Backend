<?php

namespace Modules\Acl\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static updateOrCreate(array $array)
 */
class DropshipperSetting extends Model
{
    protected $fillable = [
        'name'
    ];
    protected $table = 'dropshipper_setting';
    public $timestamps = true;
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];
    public $searchRelationShip = [];
    protected static $rules = [

    ];
    public static function getValidationRules()
    {
        return self::$rules;
    }

}
