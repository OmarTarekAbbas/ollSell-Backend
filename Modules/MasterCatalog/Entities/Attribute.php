<?php

namespace Modules\MasterCatalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//todo change
class Attribute extends Model
{
    use HasFactory;
    protected $table = 'attributes';
    protected $fillable = ['name', 'supplier_id', 'status'];

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public static $rule = [
        'name' => 'required|unique:attributes',
        'options.*.name' => 'required'
    ];

    /**
     * The function returns an array of validation rules by merging two arrays.
     *
     * return An array that is the result of merging two arrays: `self::` and
     * `self::`.
     */
    public static function getValidationRules()
    {
        return self::$rule;
    }

    /**
     * This function deletes the options of a data object when the data object is deleted.
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($data) {
            $data->options()->delete();
        });
    }

    public function options()
    {
        return $this->hasMany(AttributeOption::class);
    }
}
