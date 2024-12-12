<?php

namespace Modules\Subscription\Entities;

use Illuminate\Database\Eloquent\Model;
//todo change
class Feature extends Model
{
    protected $fillable = [];

    protected $with = ['name'];
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
    /**
     * This function returns the validation rules.
     *
     * return The `getValidationRules()` function is returning the static property ``.
     */
    public static function getValidationRules()
    {
        return self::$rules;
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_features');
    }

    public static function translationKey()
    {
        return ['name'];
    }

    public function translation()
    {
        return $this->morphMany(Translation::class, 'feature');
    }

    public function name()
    {
        return $this->morphone(Translation::class, 'feature')
            ->where('key', 'name')
            ->where('language_id', languageId());
    }

    public function nameValue($lang)
    {
        return $this->translation()->where('language_id', $lang->id)->first()->value;
    }
}
