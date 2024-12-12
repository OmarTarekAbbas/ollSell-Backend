<?php

namespace Modules\Acl\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static updateOrCreate(array $array)
 */
class DropshipperTargetMarket extends Model
{
    protected $fillable = [
        'dropshipper_id', 'target_market_id'
    ];

    /* `protected  = 'dropshipper_target_markets';` is setting the name of the database table
    that the `DropshipperTargetMarket` model is associated with. In this case, the table name is
    `dropshipper_target_markets`. This is useful when the table name does not follow Laravel's
    naming conventions (i.e. pluralized model name as table name). */
    protected $table = 'dropshipper_target_markets';

    public $timestamps = true;

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];
    public $searchRelationShip = [];
}
