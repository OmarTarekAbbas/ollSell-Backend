<?php

namespace Modules\Acl\Http\Resources\Dropshipper;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DropshipperPaymentResource extends JsonResource
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
            'bank_name'=>$this->bank_name,
            'account_number'=>$this->account_number,
            'beneficiary_mobile'=>$this->beneficiary_mobile,
            'beneficiary_name'=>$this->beneficiary_name,
            'beneficiary_address'=>$this->beneficiary_address,
            'iban'=>$this->iban,
            'swift_number'=>$this->swift_number,
            'bank_address'=>$this->bank_address,
            'currency'=>$this->currency,
            'created_at'=>Carbon::parse($this->created_at)->format('D d F Y'),
            'is_main'=>$this->is_main,
        ];
    }
}
