<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Remark extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = ['name', 'sub_status_id'];

   /**
     * This function returns a collection of all the orders that belong to this user.
     *
     * return A collection of Order objects.
     */
    public function attemptsLog()
    {
        return $this->hasMany(AttemptsLog::class);
    }

    // sub status
    public function subStatus()
    {
        return $this->belongsTo(SubStatus::class, 'sub_status_id', 'id', 'sub_statuses');
    }
}
