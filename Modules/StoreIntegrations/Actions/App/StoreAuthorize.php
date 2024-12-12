<?php

namespace Modules\StoreIntegrations\Actions\App;

use Illuminate\Support\Str;
use App\Mail\EmailAndPasswordMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Acl\Entities\Dropshipper;
use Modules\Basic\Actions\BaseAction;
use Modules\Store\Service\SallaAuthService;
use League\OAuth2\Client\Token\AccessToken;
use Modules\StoreIntegrations\Http\Services\DropshipperEcommerceService;

class StoreAuthorize extends BaseAction
{
    private $service;

    public function __construct(SallaAuthService $service)
    {
        $this->service = $service;
    }

    public function handle()
    {
        $storeDetails = $this->service->getResourceOwner(new AccessToken($this->request->data));

        $password = Str::random(8);
        $new = false;

        $dropshipper = Dropshipper::where('email', $storeDetails->getEmail())->first();

        if(!$dropshipper) {
            $dropshipper = Dropshipper::create([
                'email' => $storeDetails->getEmail(),
                'name' => $storeDetails->getName(),
                'store_name' => $storeDetails->getStoreName(),
                'phone' => $storeDetails->getMobile(),
                'isVerified' => 1,
                'password' => Hash::make($password),
                'onboarding_questionnaire_number' => 0,
            ]);

            $new = true;
        }

        /**
         * Save the tokens for later use.
         */
        $dropshipper->sallaToken()->create([
            'merchant_id'      => $storeDetails->getStoreId(),
            'access_token'  => $this->data['access_token'],
            'expires_in'    => $this->data['expires'],
            'refresh_token' => $this->data['refresh_token'],
            'store_name'    => $storeDetails->getStoreName(),
            'store_domain'  => $storeDetails->getStoreDomain(),
        ]);

        Log::channel('salla')->info("email :" . $dropshipper->email . " & password:" . $password);

        app(DropshipperEcommerceService::class)->storeEasyMode($storeDetails, $dropshipper);

        if($new)
            $this->sendEmailAndPassword($dropshipper->email, $password);
    }

    private function sendEmailAndPassword($email, $password)
    {
        Mail::to($email)->send(new EmailAndPasswordMail($email, $password));
    }
}
