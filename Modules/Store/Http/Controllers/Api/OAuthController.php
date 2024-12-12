<?php

namespace Modules\Store\Http\Controllers\Api;

use Modules\Store\Service\SallaAuthService;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Illuminate\Routing\Controller;
use Modules\Basic\Traits\ApiResponseTrait;
use Modules\Store\Service\DropshipperEcommerceService;
use Validator;
use Illuminate\Support\Facades\Auth;

class OAuthController extends Controller
{
    use ApiResponseTrait;
  /**
     * @var SallaAuthService
     */
    private $service;
    private $dropecommerce;
    public function __construct(SallaAuthService $service,DropshipperEcommerceService $dropecommerce)
    {
        //todo change
        $this->middleware('auth:dropshipper');

        $this->service = $service;
        $this->dropecommerce = $dropecommerce;
    }

    public function redirect()
    {
        $data['url']=$this->service->getProvider()->getAuthorizationUrl();
        return $this->apiResponse($data);

    }

    public function callback(Request $request)
    {


        abort_if($this->service->isEasyMode(), 401,'The Authorization mode is not supported');

        // Try to obtain an access token by utilizing the authorisations code grant.
        try {
            $token = $this->service->getAccessToken('authorization_code', [
                'code' => $request->code ?? ''
            ]);

            /** @var \Salla\OAuth2\Client\Provider\SallaUser $user */
            $user = $this->service->getResourceOwner($token);
            if($this->dropecommerce->userNameMerchant($user)){
                return $this->apiResponse(message: trans('validation.merchant_username_unique'));
            }


            $this->dropecommerce->store($user);
            /**
             *  {
             *      "id": 181690847,
             *      "name": "eman elsbay",
             *      "email": "user@salla.sa",
             *      "mobile": "555454545",
             *      "role": "user",
             *      "created_at": "2018-04-28 17:46:25",
             *      "store": {
             *        "id": 633170215,
             *        "owner_id": 181690847,
             *        "owner_name": "eman elsbay",
             *        "username": "good-store",
             *        "name": "متجر الموضة",
             *        "avatar": "https://cdn.salla.sa/XrXj/g2aYPGNvafLy0TUxWiFn7OqPkKCJFkJQz4Pw8WsS.jpeg",
             *        "store_location": "26.989000873354787,49.62477639657287",
             *        "plan": "special",
             *        "status": "active",
             *        "created_at": "2019-04-28 17:46:25"
             *      }
             *    }
             */
            // var_export($user->toArray());

            // echo 'User ID: '.$user->getId()."<br>";
            // echo 'User Name: '.$user->getName()."<br>";
            // echo 'Store ID: '.$user->getStoreID()."<br>";
            // echo 'Store Name: '.$user->getStoreName()."<br>";

            //
            // 🥳
            //
            // You can now save the access token and refresh token in your database
            // with the merchant details and redirect him again to Salla dashboard (https://s.salla.sa/apps)


            $data['access_token'] = $token->getToken();
            $data['expires_in'] =$token->getExpires();
            $data['refresh_token'] = $token->getRefreshToken();


            Auth::guard('dropshipper')->user()->sallatoken()->delete();

            // $request->user("dropshipper")->token()->create([
            //     'access_token'  => $token->getToken(),
            //     'expires_in'    => $token->getExpires(),
            //     'refresh_token' => $token->getRefreshToken()
            // ]);

            Auth::guard('dropshipper')->user()->sallatoken()->create([
                'merchant'  => $user->getID(),
                'access_token'  => $token->getToken(),
                'expires_in'    => $token->getExpires(),
                'refresh_token' => $token->getRefreshToken()
            ]);


            return $this->apiResponse($data);

        } catch (IdentityProviderException $e) {
            // Failed to get the access token or merchant details.
            // show an error message to the merchant with good UI
            return $this->apiResponse(
            $data = [], $message = "error", $code = 400,$e->getMessage()
        );

        }


    }
}
