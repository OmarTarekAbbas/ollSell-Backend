<?php

namespace Modules\CoreData\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Basic\Entities\Translation;

class Language extends Model
{
    /* `` is an array that specifies which attributes of the model are mass assignable. In
    this case, it allows the `status`, `order`, `code`, and `name` attributes to be set in bulk
    using the `create` or `update` methods. Any other attributes not listed in `` will be
    protected from mass assignment. This is a security feature to prevent unintended changes to the
    model's data. */
    protected $fillable = [
        'status', 'order', 'code', 'name'
    ];
    protected $table = 'languages';
    public $timestamps = true;
    public $searchRelationShip = [];

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public static $rules = [
        'name' => 'required|string|unique:languages',
        'code' => 'required|string|unique:languages',
        'order' => 'required|numeric|unique:languages',
    ];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];

    /**
     * This function returns the validation rules.
     * 
     * return The `getValidationRules()` function is returning the static property ``.
     */
    public static function getValidationRules()
    {
        return self::$rules;
    }

    /**
     * This PHP function returns a collection of Translation objects associated with a model.
     * 
     * return A relationship between two models is being returned. Specifically, a "has many"
     * relationship between the current model and the Translation model.
     */
    public function translation()
    {
        return $this->hasMany(Translation::class);
    }
}
