<?php

namespace Modules\CoreData\Entities;

use Illuminate\Database\Eloquent\Model;

class MetaCategory extends Model
{
    protected $fillable = [
        'name', 'category_id'
    ];
    protected $table = 'meta_categories';
    public $timestamps = true;

    public static $rules = [];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [
        'name' => 'like',
    ];
    public $searchRelationShip = [];

    /**
     * This PHP function returns an empty array for a translation key.
     * 
     * return An empty array is being returned.
     */
    public static function translationKey()
    {
        return [];
    }

    /**
     * This PHP function defines a relationship between the current model and the Category model.
     * 
     * return A relationship between the current model and the Category model is being returned.
     * Specifically, a "belongsTo" relationship is being defined, indicating that the current model
     * belongs to a single instance of the Category model.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
