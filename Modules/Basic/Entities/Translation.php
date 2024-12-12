<?php

namespace Modules\Basic\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\CoreData\Entities\Language;

class Translation extends Model
{


    protected $fillable = [
        'key', 'value', 'language_id'
    ];
    protected $table = 'translations';
    public $searchRelationShip  = [];
    public $timestamps = true;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [];

    /**
     * This is a PHP function that returns a polymorphic relationship.
     * 
     * return The `translation()` function is returning a polymorphic relationship using the
     * `morphTo()` method. This allows the model to belong to multiple other models on a single
     * association.
     */
    public function translation()
    {
        return $this->morphTo();
    }

    /**
     * This is a PHP function that returns a belongsTo relationship with a Language model based on the
     * language_id attribute.
     * 
     * return A relationship between the current model and the Language model, where the foreign key
     * used is 'language_id'.
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];
}
