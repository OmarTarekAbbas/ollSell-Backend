<?php

namespace Modules\Order\Entities;

use Modules\Order\Entities\Remark;
use Modules\CoreData\Entities\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class SubStatus extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = ['active', 'name', 'status_id'];

    public $searchConfig = [
        'name' => 'like',
    ];

    public static $rules = [];

    public static function getValidationRules()
    {
        return self::$rules;
    }

    public function remarks()
    {
        return $this->hasMany(Remark::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * This function returns a collection of all the orders that belong to this user.
     *
     * return A collection of Order objects.
     */
    public function attemptsLog()
    {
        return $this->hasMany(AttemptsLog::class);
    }
}
