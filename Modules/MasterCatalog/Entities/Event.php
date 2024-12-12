<?php

namespace Modules\MasterCatalog\Entities;
//todo change
use Modules\Basic\Entities\Media;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory, Loggable;

    protected $fillable = ["title","description","fromDate","toDate","status"];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public static $rules = [
        'title' => 'required|max:255',
        'fromDate' => 'required|date',
        'toDate' => 'required|date|after:fromDate',
        'image' => 'image|mimes:jpg,jpeg,png,gif',
        'status' => 'required|in:0,1'
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
    /**
     * This PHP function returns a polymorphic relationship between the current object and the Media
     * model.
     *
     * return The `media()` function is returning a morphMany relationship between the current model
     * and the `Media` model, where the `category` column of the `Media` table is used to store the
     * type of the related model.
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'category');
    }

    /**
     * The function returns the logo media of an object.
     *
     * return The `logo()` function is returning a query builder instance that retrieves the media
     * associated with the current object (likely a model) where the media type is equal to the value
     * of the `lm` key in the `mediaType()` array.
     */
    public function image()
    {
        return $this->media()->whereType(mediaType()['im']);
    }

    public function products(){
        return $this->belongsToMany(Product::class)->orderByDesc('quantity');
    }
}
