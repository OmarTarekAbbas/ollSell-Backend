<?php

namespace Modules\Acl\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Acl\Http\Requests\Login\Api\LoginRequest;
use Modules\Basic\Http\Controllers\BasicController;
use Illuminate\Support\Facades\Hash;
use Modules\Acl\Http\Requests\Dropshipper\Api\CreateChangePhoneNumberRequest;
use Modules\Acl\Http\Requests\Dropshipper\Api\CreateStepOneRequest;
use Modules\Acl\Http\Requests\Dropshipper\Api\CreateStepThreeRequest;
use Modules\Acl\Service\DropshipperService;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperResource;

/**
 * @group Authentication
 *
 * APIs for managing authentication
 */
class AuthController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that initializes a DropshipperService object.
     *
     * param DropshipperService Service The parameter "Service" is an instance of the
     * "DropshipperService" class that is being injected into the constructor of another class. This is
     * a common practice in object-oriented programming and is known as dependency injection. By
     * injecting the service as a dependency, the class can use its methods and properties
     */
    public function __construct(DropshipperService $Service)
    {
        $this->service = $Service;
    }

    /**
     * Login Api
     *
     * The Login endpoint allows dropshippers to authenticate themselves and obtain an
     * access token for accessing protected resources within the Dropshipping Platform.
     * This endpoint uses the OAuth2.0 security protocol for secure authentication.
     *
     * If the authentication is successful, the API will respond with a JSON
     * object containing the access token and other relevant information.
     */
    public function login(LoginRequest $request)
    {
        $dropshipper = $this->service->findBy($request, get: 'first');
        if ($dropshipper) {
            if (!Hash::check($request->password, $dropshipper->password)) {
                return $this->unauthorizedResponse(trans('auth.Wrong password'));
            }
            if ($dropshipper->status == activeType()['us']) {
                return $this->unauthorizedResponse(trans('auth.The account has been disabled by the administration'));
            }
            $token = $dropshipper->createToken('olltek Personal Access Client')->accessToken;
            $dropshipper->update(['token' => $token]);
            if ($dropshipper->code) {
                return $this->apiResponse(new DropshipperResource($dropshipper));
            } else {
                return $this->apiResponse(
                    new DropshipperResource($dropshipper)
                );
            }
        } else {
            return $this->unauthorizedResponse(trans('auth.failed'));
        }
    }

    /**
     * Email verification
     *
     * The Email Verification endpoint allows dropshippers to verify their email addresses
     * by providing a verification code. This endpoint is used to confirm the ownership and
     * validity of the email associated with a dropshipper's account.
     *
     * This endpoint verifies the email address of the authenticated user. The dropshipper
     * needs to provide the verification code received via email to complete the verification
     * process. Upon successful verification, the email address is marked as verified, granting
     * the dropshipper full access to the platform's features.
     *
     * @authenticated
     */
    public function email(Request $request)
    {
        //todo change
        $user = auth()->user();
        if ($user->code == $request->code && $request->code != "") {
            $user->update(['email_verification' => 1, 'code' => null]);
            return $this->apiResponse(new DropshipperResource(user()), trans('auth.verification done'));
        }
        return $this->apiValidation(trans('auth.wrong code'));
    }

    /**
     * Check Email
     *
     * The Check Email endpoint allows dropshippers to verify if a given email address exists in the system.
     * This endpoint can be used to validate email addresses during the registration or account recovery process.
     *
     * This endpoint checks the provided email address against the existing records in the system.
     * If the email address is found, it indicates that the email is correct and associated with
     * an existing dropshipper account. If the email address is not found, it indicates that
     * the email is incorrect or does not exist in the system.
     */
    public function checkEmail(Request $request)
    {
        if (isset($request->email) && !empty($request->email)) {
            $count = $this->service->findBy($request, get: "count");
            if ($count == 1) {
                return $this->apiResponse([], trans('auth.email correct'));
            }
            return $this->apiValidation(trans('auth.wrong email'));
        }
    }

    /**
     * This PHP function checks if the input code is equal to 000000 and returns a response
     * accordingly.
     *
     * param Request request  is an object of the Request class, which is used to handle HTTP
     * requests in Laravel. It contains the data sent in the request, such as form data, query
     * parameters, and headers. In this function, it is used to retrieve the 'code' parameter from the
     * request.
     *
     * return If the code in the request is not equal to 000000, the function will return an API
     * validation error message "wrong code". Otherwise, it will return an API response message "code
     * done" with an empty array.
     */
    public function emailCode(Request $request)
    {
        if ($request->code != 000000) {
            return $this->apiValidation(trans('auth.wrong code'));
        }
        return $this->apiResponse([], trans('auth.code done'));
    }

    /**
     * Resend Code
     *
     * The Resend Verification Code endpoint allows dropshippers to request a new
     * verification code to be sent to their phone number. This endpoint is
     * used when the dropshipper did not receive the initial verification
     * code or needs a new code for any reason.
     *
     * This endpoint triggers the generation and sending of a new verification
     * code to the dropshipper's phone number. The new code allows the dropshipper
     * to verify their phone number and complete the verification process.
     *
     * If the request is successful and the code is resent,
     * the API will respond with a success message containing the new verification code.
     *
     * @authenticated
     *
     */
    public function resendCode()
    {
        if (!user()) {
            // Return a 401 response
            return $this->apiResponse($data = [], $message = 'Unauthorized', 401);
        }
        $data = $this->service->resendCode();

        if($data)
        {
            return $this->createResponse(['code' => ''], __('app.verify_email'));
        }
        return $this->unKnowError();
    }

    /**
     * Step One Registration
     *
     * The Step One Registration endpoint allows users to initiate the registration process by providing
     * the necessary information for the first step. This endpoint handles the initial step of the
     * registration process and sends a verification code to the user's phone number.
     *
     * This endpoint receives the necessary information to complete the first step of the registration process.
     * The user needs to provide the required details, such as their name, email address, phone number,
     * and any other relevant information as per the request parameters. Upon successful submission,
     * a verification code will be sent to the user's phone number.
     */
    public function stepOneRegister(CreateStepOneRequest $request)
    {
        $data = $this->service->storeStepOne($request);
        if ($data) {
            return $this->createResponse($data, trans('auth.The verification code has been sent to your email'));
        }
        return $this->unKnowError();
    }

    /**
     * Step Three Registration
     *
     * The Step Three Registration endpoint allows users to complete the final step of the registration
     * process by providing the necessary information. This endpoint handles the submission of
     * the required details to finalize the registration and create the user's account.
     *
     */
    public function stepThreeRegister(CreateStepThreeRequest $request)
    {
        $data = $this->service->storeStepThree($request);
        if ($data) {
            return $this->updateResponse($data, trans('auth.Register done'));
        }
        return $this->unKnowError(trans('auth.invalidResetCode'));
    }

    /**
     * Change phone number
     *
     * The Change Phone Number endpoint allows dropshippers to update their phone number
     * associated with their account. This endpoint provides a way for dropshippers
     * to keep their contact information up to date.
     *
     * This endpoint allows dropshippers to change their phone number by providing
     * the necessary information. The dropshipper needs to provide the new phone
     * number along with any other required details as per the request parameters.
     *
     * @authenticated
     */
    public function changePhoneNumber(CreateChangePhoneNumberRequest $request)
    {
        $data = $this->service->changePhoneNumber($request);
        if ($data) {
            return $this->updateResponse($data, trans('auth.Change Phone Number'));
        }
        return $this->unKnowError(trans('auth.invalidResetCode'));
    }
}
