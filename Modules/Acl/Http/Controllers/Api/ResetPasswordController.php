<?php

namespace Modules\Acl\Http\Controllers\Api;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Acl\Entities\Dropshipper;
use Modules\Acl\Service\DropshipperService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Acl\Notifications\ForgetPasswordNotification;
use Modules\Acl\Service\ResetPasswordService;

/**
 * @group Authentication
 *
 * APIs for managing resetting password
 */
class ResetPasswordController extends BasicController
{
    private $service,$dropshipperService;

    /**
     * This is a constructor function that initializes a DropshipperService object.
     *
     * param DropshipperService Service The parameter "Service" is an instance of the
     * "DropshipperService" class that is being injected into the constructor of the current class.
     * This is a common practice in dependency injection, where the dependencies of a class are passed
     * in as constructor parameters rather than being instantiated within the class itself. This
     */
    public function __construct(ResetPasswordService $Service, DropshipperService $dropshipperService)
    {
        $this->service = $Service;
        $this->dropshipperService = $dropshipperService;
    }

    /**
     * Reset password link
     *
     * The Reset Password Link endpoint allows dropshippers to generate a reset password link
     * that can be sent to their registered email address. This link enables them to reset
     * their password and regain access to their account.
     */
    public function resetPasswordLink(Request $request)
    {
        //todo change
        $request->validate(['email' => 'required|exists:dropshippers']);
        $dropShipper = $this->dropshipperService->findBy(request: $request, get: "first");
        $token = Str::random(64);
        $this->service->findBy(new Request(['email' => $dropShipper->email,]), get: 'delete');
        $request->merge([
            'email' => $dropShipper->email,
            'token' => $token,
            'created_at' => now()
        ]);
        $this->service->store($request);
        // email user with token
        // forget password notification
        $dropShipper->notify(new ForgetPasswordNotification(token: $token));
        return $this->apiResponse([], trans('auth.Check your email for Reset password link'));
    }

    public function resetPasswordCode(Request $request)
    {
        //todo change
        $request->validate(['email' => 'required|exists:dropshippers']);
        $dropShipper = $this->dropshipperService->findBy(request: $request, get: "first");
        $token =rand(1000, 9999);
      
        $this->service->findBy(new Request(['email' => $dropShipper->email,]), get: 'delete');
        $request->merge([
            'email' => $dropShipper->email,
            'token' => $token,
            'created_at' => now()
        ]);
        $this->service->store($request);
        // email user with token
        // forget password notification
        $this->dropshipperService->sendMailCode($dropShipper,$token);
        return $this->apiResponse([], trans('auth.Check your email for Reset password code'));
    }



    public function verificationCode(Request $request){

        $request->validate(['email' => 'required|exists:dropshippers'
    , 'code' => 'required'
    ]);
    $dropShipper = $this->dropshipperService->findBy(request: $request, get: "first");
        $data   = $this->dropshipperService->verificationCode($dropShipper,$request);
       
        if($data === 'invalidTime')
        {
            return $this->unKnowError(trans('auth.The verification code is old, please send a new code'));
        }
        if($data)
        {
            return $this->createResponse($data, __('app.welcome_aboard'));
        }
        return $this->unKnowError(trans('auth.invalidResetCode'));

   
    }
    

    /**
     * Reset password
     *
     * This endpoint receives the necessary parameters to reset the password.
     * The dropshipper needs to provide the new password and the reset token received via email.
     * Upon successful verification of the reset token, the account password will be updated accordingly.
     *
     */
    public function resetPassword(Request $request)
    {
        //todo change
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
        $passwordReset = $this->service->findBy(new Request(['token' => request('token')]), get: 'first');
        if(!$passwordReset)
        {
            throw new Exception(trans('auth.Invalid or expired Token!'));
        }
        $dropShipper = Dropshipper::where('email', $passwordReset->email)->first();
        $dropShipper->update(['password' => Hash::make(request('password'))]);
        $passwordReset->delete();
        return $this->apiResponse([], trans('auth.Password reset successfully'));
    }
}
