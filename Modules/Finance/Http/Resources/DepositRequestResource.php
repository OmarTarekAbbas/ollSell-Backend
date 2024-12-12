<?php

namespace Modules\Finance\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperResource;

class DepositRequestResource extends JsonResource
{
    /**
     * It returns the data in the form of an array.
     *
     * param request The incoming request.
     *
     * return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'dropshipper_id' => $this->dropshipper_id,
            'dropshipper' => new DropshipperResource($this->dropshipper),
            'status' => $this->status,
            'amount' => $this->amount,
            'reason' => $this->reason ?? '',
            'avatar' =>  $this->avatar ? getFile($this->avatar->file ?? null, pathType()['ip'], getFileNameServer($this->avatar)) : asset('dashboard') . '/assets/media/svg/avatars/blank.svg',
        ];
    }
}
