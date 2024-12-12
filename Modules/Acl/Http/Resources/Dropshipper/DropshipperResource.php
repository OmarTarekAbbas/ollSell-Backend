<?php

namespace Modules\Acl\Http\Resources\Dropshipper;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Store\Transformers\EcommerceResource;
use Modules\CoreData\Http\Resources\TargetMarket\TargetMarketListResource;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperSegmentationResource;

class DropshipperResource extends JsonResource
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
            'profit' => number_format((float)$this->profit, 2, '.', ''),
            'store_name' => $this->store_name ?? "",
            'email' => $this->email ?? "",
            'phone' => $this->phone ?? "",
            'first_name' => $this->first_name ?? "",
            'second_name' => $this->second_name ?? "",
            'blocked' => $this->blocked,
            'isVerified' => $this->isVerified,
            'walletBalance' => $this->walletBalance ?? 0,
            'profitBalance' => $this->profitBalance ?? 0,
            'earningsWithdrawal' => $this->earningsWithdrawal ?? 0,
            'target_market' =>  TargetMarketListResource::collection($this->targetMarket),
            'token' => $this->token ?? "",
            'dataState' => $this->stepStatus(),
            'avatar' =>  $this->avatar ? getFile($this->avatar->file ?? null, pathType()['ip'], getFileNameServer($this->avatar)) : asset('dashboard') . '/assets/media/svg/avatars/blank.svg',
            'salla' => EcommerceResource::collection($this->dropshipperEcommerces),
            'expirePlanAt' => $this->expirePlanAt,
            'totalNotifications' => $this->totalNotifications,
            'sallaStore' => $this->sallatoken ? true : false,
            'segmentation'=> $this->segmentation ? new DropshipperSegmentationResource($this) : [],
            'onboarding_questionnaire_number'=>$this->onboarding_questionnaire_number,
            'role'=>__('app.role'),
        ];
    }

    /**
     * It returns an array of booleans that are true if the user has completed the step, and false if
     * they haven't
     *
     * return An array of booleans.
     */
    public function stepStatus()
    {
        return [
            'stepOne' => ($this->email) ? true : false,
            'stepTwo' => ($this->isVerified) ? true : false,
            'stepThree' => ($this->targetMarket->count() != 0) ? true : false,
        ];
    }
}
