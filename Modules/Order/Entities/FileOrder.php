<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Acl\Entities\Dropshipper;

class FileOrder extends Model
{
    /* In this PHP code, `protected ` is an array that specifies which attributes of the
    `FileProduct` model are fillable. Fillable attributes can be mass assigned using the `create` or
    `update` methods. In this case, the `file` and `supplier_id` attributes are fillable. */
    protected $fillable = ['file', 'count', 'countSuccess', 'countFail', 'dropshipper_id'];
    protected $table = 'file_orders';

    /**
     * The function "supplier" returns the relationship between the current object and the Supplier
     * model.
     * 
     * @return a relationship between the current model and the Supplier model.
     */
    public function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class);
    }
}
