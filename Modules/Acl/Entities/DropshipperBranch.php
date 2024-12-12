<?php

namespace Modules\Acl\Entities;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Basic\Entities\Media;
use Modules\Order\Entities\Invoice;
use Modules\Order\Entities\Order;

class DropshipperBranch extends Authenticatable
{
    protected $fillable = [
        'company_name', 'email_address', 'address', 'city', 'state', 'main', 'dropshipper_id', 'code'
    ];

    /* `protected  = 'dropshipper_target_markets';` is setting the name of the database table
    that the `DropshipperTargetMarket` model is associated with. In this case, the table name is
    `dropshipper_target_markets`. This is useful when the table name does not follow Laravel's
    naming conventions (i.e. pluralized model name as table name). */
    protected $table = 'dropshipper_branches';

    public $timestamps = true;

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];
    public $searchRelationShip = [];


    /**
     * This is a PHP function that returns a relationship between a model and a Dropshipper model.
     *
     * return The function `dropshipper()` is returning a `belongsTo` relationship between the current
     * model and the `Dropshipper` model, with the foreign key `dropshipper_id`.
     */
    public function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class, 'dropshipper_id');
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

    /**
     * This function returns a collection of all the orders that belong to this user.
     *
     * return A collection of Order objects.
     */
    public function order()
    {
        return $this->hasMany(Order::class, 'id');
    }

    /**
     * This function returns a collection of all the orders that belong to this user.
     *
     * return A collection of Order objects.
     */
    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * This function deletes related data when a model is deleted.
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($data) {
            $data->media()->delete();
        });
    }
}
