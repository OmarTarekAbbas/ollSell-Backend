<?php

namespace Modules\Order\Entities;


use Illuminate\Database\Eloquent\Model;

class Fake extends Model
{


    protected $fillable = [
        'customerPhone'
    ];

    /* Telling the model to use the profits table. */
    protected $table = 'fakes';

    /* Telling the model to use the timestamps created_at and updated_at. */
    public $timestamps = true;

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];
    public $searchRelationShip = [];
    public static $rules = [];

    /**
     * This function returns the validation rules for the model.
     *
     * return The rules for the model.
     */
    public static function getValidationRules()
    {
        return self::$rules;
    }

    /**
     * This function returns an array of all the keys that are used in the translation files.
     *
     * return An array of the keys that are to be translated.
     */
    public static function translationKey()
    {
        return [];
    }
}