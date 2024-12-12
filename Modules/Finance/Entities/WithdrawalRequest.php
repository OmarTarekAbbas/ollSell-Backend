<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Acl\Entities\Dropshipper;
use Modules\Acl\Entities\DropshipperPayment;
use Modules\Basic\Entities\Media;

//todo change
class WithdrawalRequest extends Model
{
    /* These are constants defined in the `WithdrawalRequest` model class in PHP. They represent the
    possible values for the `status` attribute of a withdrawal request, which can be either
    "pending", "rejected", or "approved". By defining these constants, it makes it easier to
    reference these values throughout the code and reduces the risk of typos or inconsistencies. */
    const PENDING_STATUS = 'pending';
    const INPROGRESS_STATUS = 'inProgress';
    const APPROVED_STATUS = 'approved';
    const REJECTED_STATUS = 'rejected';

    /* `protected ` is an array that specifies which attributes of the model are fillable. In
    this case, it allows the `dropshipper_id`, `status`, `amount`, and `reason` attributes to be
    mass assignable. This means that these attributes can be set using the `create` or `update`
    methods on the model. All other attributes will be guarded and cannot be mass assigned. This is
    a security feature to prevent unintended changes to the model's data. */
    protected $fillable = [
        'dropshipper_id',
        'status',
        'amount',
        'reason',
        'dropshipper_payment_id',
        'total_amount_dropshipper',
        'balance_dropshipper',
        'withdraw_dropshipper',
        'order_id'
    ];


    /* These are attributes of the `WithdrawalRequest` model class in PHP: */
    protected $table = 'withdrawal_requests';
    public $timestamps = true;
    public $searchRelationShip = [];
    public $searchConfig = ['created_at' => 'date'];
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

    public function dropshipper_payment()
    {
        return $this->belongsTo(DropshipperPayment::class);
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
     * The `chats` function defines a relationship where a model has many `ChatWithdrawalRequest`
     * instances.
     * 
     * @return The `chats()` function is returning a relationship definition for a "has many"
     * relationship with the `ChatWithdrawalRequest` model. This means that the current model has
     * multiple `ChatWithdrawalRequest` records associated with it.
     */
    public function chats()
    {
        return $this->hasMany(ChatWithdrawalRequest::class);
    }

    /**
     * The function `canOpenChat` checks if the status is either pending or in progress to determine if
     * the chat can be opened.
     * 
     * @return The function `canOpenChat()` is returning a boolean value based on whether the status of
     * the object is either `PENDING_STATUS` or `INPROGRESS_STATUS`.
     */
    public function canOpenChat()
    {
        return in_array($this->status, [self::PENDING_STATUS, self::INPROGRESS_STATUS]);
    }

    /**
     * The function `canCloseChat` checks if the status of a chat is either approved or rejected.
     * 
     * @return The function `canCloseChat()` is returning a boolean value based on whether the current
     * status of the chat is either `APPROVED_STATUS` or `REJECTED_STATUS`. If the status is one of
     * these two values, the function will return `true`, indicating that the chat can be closed.
     * Otherwise, it will return `false`.
     */
    public function canCloseChat()
    {
        return in_array($this->status, [self::APPROVED_STATUS, self::REJECTED_STATUS]);
    }
}
