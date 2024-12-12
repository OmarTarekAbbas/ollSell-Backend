<?php

namespace Modules\MasterCatalog\Entities;

use Illuminate\Database\Eloquent\Model;
class ProductLog extends Model
{

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    protected $fillable = [
       'quantity', 'type','product_id','user_id'
    ];
    protected $table = 'product_logs';
    public $timestamps = true;
    public $searchRelationShip = [
    ];
    /**
     * @inheritdoc
     */
    protected $dates = ['created_at'];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
