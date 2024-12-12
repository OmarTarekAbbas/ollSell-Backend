<?php

namespace Modules\Order\Entities;

use Modules\CoreData\Entities\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class AttemptsLog extends Model
{
    use Loggable;

    protected $fillable = ['order_id', 'status_id', 'sub_status_id', 'remark_id', 'attempts_count', 'validated_at', 'first_validation', 'last_edit_order', 'notes'];

    public $searchConfig = [
        'name' => 'like',
    ];

    public static $rules = [];

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

    /**
     * This is a PHP function that returns a relationship between the current object and a Status
     * object.
     *
     * return A relationship between the current model and the `Status` model, where the foreign key
     * `status_id` is used to link the two models.
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    /**
     * This is a PHP function that returns a relationship between the current object and a Status
     * object.
     *
     * return A relationship between the current model and the `Status` model, where the foreign key
     * `status_id` is used to link the two models.
     */
    public function orders()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * This is a PHP function that returns a relationship between the current object and a Status
     * object.
     *
     * return A relationship between the current model and the `Status` model, where the foreign key
     * `status_id` is used to link the two models.
     */
    public function subStatus()
    {
        return $this->belongsTo(SubStatus::class, 'sub_status_id');
    }

    /**
     * This is a PHP function that returns a relationship between the current object and a Status
     * object.
     *
     * return A relationship between the current model and the `Status` model, where the foreign key
     * `status_id` is used to link the two models.
     */
    public function remarks()
    {
        return $this->belongsTo(Remark::class, 'remark_id');
    }
}
