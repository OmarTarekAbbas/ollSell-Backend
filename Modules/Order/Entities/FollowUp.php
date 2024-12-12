<?php

namespace Modules\Order\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'activity_type',
        'title',
        'content',
        'created_at'
    ];

    public const ACTIVITY_PHONE_CALL = 'phone call';
    public const ACTIVITY_MESSAGE = 'message';
    public const ACTIVITY_MEETING = 'meeting';
    public const ACTIVITY_NOTE = 'note';

    public static function getActivityTypesWithAttributes()
    {
        return [
            self::ACTIVITY_PHONE_CALL => [
                'name' => 'Phone Call',
                'icon' => 'phone',
                'color' => 'blue',
                'value' => self::ACTIVITY_PHONE_CALL,
            ],
            self::ACTIVITY_MESSAGE => [
                'name' => 'Message',
                'icon' => 'message',
                'color' => 'green',
                'value' => self::ACTIVITY_MESSAGE,
            ],
            self::ACTIVITY_MEETING => [
                'name' => 'Meeting',
                'icon' => 'calendar',
                'color' => 'orange',
                'value' => self::ACTIVITY_MEETING,
            ],
            self::ACTIVITY_NOTE => [
                'name' => 'Note',
                'icon' => 'note',
                'color' => 'yellow',
                'value' => self::ACTIVITY_NOTE,
            ],
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
