<?php

namespace App\Models;

use Modules\Acl\Entities\Role;
use Laravel\Passport\HasApiTokens;
use Modules\Acl\Entities\RoleUser;
use Database\Factories\UserFactory;
use Illuminate\Notifications\Notifiable;
use Modules\Order\Entities\RefundMessage;
use Modules\CoreData\Entities\Notification;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Finance\Entities\ChatWithdrawalRequest;
use Modules\Order\Entities\DiscountLog;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Loggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'suspended',
        'lang',
        'totalNotifications'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * [columns that needs to have customised search such as like or where in]
     *
     * @var string[]
     */

    public array $searchConfig = [];

    public array $searchRelationShip = [
        'role_id' => 'role->role_id',
    ];

    protected $dates = [];
    public static function translationKey()
    {
        return [];
    }

    public static array $rules = [
        'name' => 'required|unique:users',
        'email' => 'required|email|unique:users',
        'role_id' => 'required|exists:roles,id',
    ];

    public static array  $getValidationResetPassword = [
        'email' => 'required|email|exists:users',
        'password' => 'required|string|min:6|confirmed',
        'password_confirmation' => 'required'
    ];

    public static array $getValidationEmail = [
        'email' => 'required|exists:users,email'
    ];
    public static array $external_users_required_data = [];

    protected static array $PasswordRules = ['password' => 'required|min:8'];
    protected static array $PasswordCreateRules = ['password' => 'required|min:8|confirmed'];

    public static function getValidationRules(): array
    {
        return array_merge(self::$rules, self::$PasswordCreateRules);
    }

    public static function getValidationRulesLogin(): array
    {
        return self::$PasswordRules;
    }

    public static function getValidationRulesUpdate(): array
    {
        return self::$rules;
    }

    public static function getValidationRulesPassword()
    {
        return self::$PasswordCreateRules;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function role()
    {
        return $this->hasOne(RoleUser::class);
    }

    public function permissions()
    {
        return $this->roles->map->permissions->flatten()->pluck('name')->unique();
    }

    public function accountType(): string
    {
        return 'App\Models\User';
    }

    public function canUpdateOrder($order)
    {
        $canUpdate = true;

        if (! auth()->check()) {
            return true;
        }

        if ($this->can('view_all_order') && $this->can('update_order')) {
            return true;
        }

        if (! $this->can('update_order')) {
            $canUpdate = false;
        }
        if ($order->operator_id && $order->operator_id != auth()->id()) {
            $canUpdate = false;
        }

        return $canUpdate;
    }

    /**
     * The function returns a new instance of the UserFactory class.
     *
     * return The method is returning an instance of the UserFactory class.
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }


    public static function boot()
    {
        parent::boot();
        static::deleting(function ($data) {});
    }

    public function token()
    {
        return $this->hasOne(OauthToken::class);
    }

    /**
     * Get all of the Refund Message.
     */
    public function refundMessages()
    {
        return $this->morphMany(RefundMessage::class, 'messagable');
    }

    /**
     * The `chats` function returns a polymorphic relationship with `ChatWithdrawalRequest` model.
     * 
     * @return The `chats()` function is returning a morphMany relationship with the
     * `ChatWithdrawalRequest` model using the `messagable` polymorphic relationship. This means that
     * the `ChatWithdrawalRequest` model can belong to multiple other models, including the model where
     * the `chats()` function is defined.
     */
    public function chats()
    {
        return $this->morphMany(ChatWithdrawalRequest::class, 'messagable');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'user');
    }

    public function notifiable()
    {
        return $this->morphMany(ExportTracking::class, 'notifiable');
    }

    public function scopeWithRole($query, $roleId)
    {
        return $query->whereHas('roles', function ($query) use ($roleId) {
            $query->where('id', $roleId);
        });
    }

    public function discounts()
    {
        return $this->hasMany(DiscountLog::class, 'operator_id');
    }
}
