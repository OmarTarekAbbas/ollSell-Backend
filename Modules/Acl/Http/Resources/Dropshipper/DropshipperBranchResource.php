<?php

namespace Modules\Acl\Http\Resources\Dropshipper;

use Modules\Store\Transformers\StoreResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CoreData\Http\Resources\TargetMarket\TargetMarketListResource;
use Modules\Subscription\Http\Resources\SubscriptionResource;

class DropshipperBranchResource extends JsonResource
{
    /**
     * This is a PHP function that converts an object into an array with specific properties and
     * formats the profit value.
     *
     * param request The  parameter is an instance of the Illuminate\Http\Request class, which
     * represents the current HTTP request being handled by the application. It contains information
     * about the request such as the HTTP method, headers, and query parameters. In this context, it is
     * not being used in the toArray() method.
     *
     * return An array of data representing a user, including their ID, profit, store and merchant
     * names, email, phone number, first and second names, verification status, wallet balance, bank
     * account information, target market, token, data state, avatar, and store details.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'companyName' => $this->company_name,
            'emailAddress' => $this->email_address,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'main' => ($this->main == 1) ? true : false,
            'code' => $this->code,
            'avatar' =>  $this->avatar ? getFile($this->avatar->file ?? null, pathType()['ip'], getFileNameServer($this->avatar)) : asset('dashboard') . '/assets/media/svg/avatars/blank.svg',
            'dropshipper_id' => new DropshipperResource($this->dropshipper)
        ];
    }
}
