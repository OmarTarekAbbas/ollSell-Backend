<?php

namespace Modules\Basic\Entities;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'file', 'category_type', 'category_id', 'type'
    ];

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    protected $table = 'medias';
    public $searchRelationShip  = [];
    public $timestamps = true;


    /**
     * This function returns a polymorphic relationship for media.
     * 
     * return The `media()` function is returning a polymorphic relationship using the `morphTo()`
     * method. This allows the model to belong to multiple other models, which are determined
     * dynamically at runtime based on the value of the `media_type` and `media_id` columns in the
     * database.
     */
    public function media()
    {
        return $this->morphTo();
    }

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];
}
