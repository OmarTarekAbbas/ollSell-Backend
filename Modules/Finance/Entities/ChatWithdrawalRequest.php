<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Acl\Entities\Dropshipper;
use Modules\Acl\Entities\DropshipperPayment;
use Modules\Basic\Entities\Media;

class ChatWithdrawalRequest extends Model
{

    protected $fillable = [
        'message',
        'withdrawal_request_id',
        'messagable_type',
        'messagable_id',
    ];

    public function messagable()
    {
        return $this->morphTo();
    }

    public function withdrawalRequest()
    {
        return $this->belongsTo(WithdrawalRequest::class);
    }
}
