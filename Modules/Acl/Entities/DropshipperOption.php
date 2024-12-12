<?php

namespace Modules\Acl\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static updateOrCreate(array $array)
 */
class DropshipperOption extends Model
{
    protected $fillable = [
        'dropshipper_id' , 'dropshipper_setting_id'
    ];
    protected $table = 'dropshipper_options';
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
    public function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class);
    }

    public function dropshipper_setting()
    {
        return $this->belongsTo(DropshipperSetting::class,'dropshipper_setting_id');
    }
}
