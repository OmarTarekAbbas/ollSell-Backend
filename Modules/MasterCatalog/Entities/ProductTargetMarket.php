<?php

namespace Modules\MasterCatalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

/**
 * @method static updateOrCreate(array $array)
 */
class ProductTargetMarket extends Model
{
    use Loggable;

    protected $fillable = [
        'product_id', 'target_market_id'
    ];

    protected $table = 'product_target_markets';

    public $timestamps = true;

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];
    public $searchRelationShip = [];
}
