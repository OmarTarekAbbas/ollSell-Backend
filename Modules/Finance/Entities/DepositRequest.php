<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Acl\Entities\Dropshipper;
use Modules\Basic\Entities\Media;
//todo change
class DepositRequest extends Model
{
    /* These are constants defined in the `DepositRequest` model class in PHP. They represent the
    possible values for the `status` attribute of a withdrawal request, which can be either
    "pending", "rejected", or "approved". By defining these constants, it makes it easier to
    reference these values throughout the code and reduces the risk of typos or inconsistencies. */
    const PENDING_STATUS = 'pending';
    const APPROVED_STATUS = 'approved';
    const REJECTED_STATUS = 'rejected';

    /* `protected ` is an array that specifies which attributes of the model are fillable. In
    this case, it allows the `dropshipper_id`, `status`, `amount`, and `reason` attributes to be
    mass assignable. This means that these attributes can be set using the `create` or `update`
    methods on the model. All other attributes will be guarded and cannot be mass assigned. This is
    a security feature to prevent unintended changes to the model's data. */
    protected $fillable = ['dropshipper_id', 'status', 'amount', 'reason'];


    /* These are attributes of the `DepositRequest` model class in PHP: */
    protected $table = 'deposit_requests';
    public $timestamps = true;
    public $searchRelationShip = [];

    /**
     * The function defines a "belongsTo" relationship between the current model and the Order model in
     * PHP.
     * 
     * return A "belongsTo" relationship between the current model and the Order model is being
     * returned.
     */
    public function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class);
    }

    /**
     * This function returns a morphOne relationship with the Media model under the category attribute.
     * 
     * return The `media()` function is returning a `morphOne` relationship between the current model
     * and the `Media` model, where the `category` column of the `Media` table is used to store the
     * type of the related model.
     */
    public function media()
    {
        return $this->morphOne(Media::class, 'category');
    }

    /**
     * This PHP function returns the avatar media of an object.
     * 
     * return The `avatar()` function is returning a query builder instance that filters the media
     * associated with the current model instance to only include those with a type of
     * `mediaType()['am']`.
     */
    public function avatar()
    {
        return $this->media()->whereType(mediaType()['am']);
    }
}
