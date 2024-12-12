<?php

namespace Modules\MasterCatalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Basic\Entities\Media;

class AttributeOption extends Model
{
    use HasFactory;
    protected $table = 'attribute_options';
    protected $fillable = ['name', 'attribute_id'];

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
    public function logo()
    {
        return $this->media()->whereType(mediaType()['lm']);
    }
}
