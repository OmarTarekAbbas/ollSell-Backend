<?php

namespace Modules\Acl\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Acl\Http\Requests\Dropshipper\Api\QuestionnaireRequest;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperOnboardingQuestionnaireAnswerResource;
use Modules\Acl\Service\DropshipperService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Acl\Http\Requests\Dropshipper\Api\CreateRequest;
use Modules\Acl\Http\Requests\Dropshipper\Api\UpdateRequest;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperResource;
use Modules\Acl\Http\Requests\Dropshipper\Api\UpdatePasswordRequest;
use Modules\Acl\Http\Requests\Dropshipper\Api\CreateMegaDropshipperRequest;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperBranchResource;
use Modules\Acl\Service\DropshipperBranchService;

/**
 * @group Dropshipper management
 *
 * APIs for managing dropshippers
 */
class DropshipperController extends BasicController
{
    private $service;
    private $dropshipperBranchService;

    public function __construct(DropshipperService $Service, DropshipperBranchService $dropshipperBranchService)
    {
        $this->service = $Service;
        $this->dropshipperBranchService = $dropshipperBranchService;
    }

    /**
     * Create Account
     *
     * The Create Account endpoint allows users to register and create a new account in the system.
     * This endpoint handles the registration process, including sending a verification code to
     * the user's email address for account activation.
     *
     * This endpoint receives the necessary information to create a new account.
     * The user needs to provide the required details, such as their name,
     * email address, and password, as per the request parameters. Upon successful registration,
     * a verification code will be sent to the user's email address.
     *
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return $this->createResponse(
                $data,
                trans('auth.The verification code has been sent to your email again, please check junk/spam folder')
            );
        }
        return $this->unKnowError();
    }

    /**
     * Update Profile
     *
     * The Update Profile endpoint allows users to update their profile information within the system.
     * This endpoint enables users to modify details such as their name, email address,
     * and other relevant information associated with their account.
     *
     * This endpoint receives the necessary parameters to update the user's profile. The user needs to
     * provide the updated information, such as their name, email address, and any other relevant details,
     * as per the request parameters.
     *
     * @authenticated
     */
    public function profile(UpdateRequest $request)
    {
        return $this->service->update($request, $id = null);
    }

    /**
     * Update Password
     *
     * The Update Password endpoint allows users to change their account password within the system.
     * This endpoint provides a way for users to update their login credentials for enhanced
     * security or to comply with password change requirements.
     *
     * This endpoint receives the necessary parameters to update the user's password.
     * The user needs to provide the old password along with the new password
     * as per the request parameters. The old password is required for
     * authentication and verification purposes.
     *
     * @authenticated
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $oldPassword = $this->isMatchingPassword($request->old_password);
        if (!$oldPassword) {
            return $this->apiValidation(trans('auth.The old password is wrong'));
        }
        return $this->service->update($request);
    }

    /**
     * Update Profit Margin
     *
     * The Update Profit Margin endpoint allows users to modify the profit margin associated with their account.
     * This endpoint enables users to adjust the profit percentage they earn from selling products or services.
     *
     * This endpoint receives the necessary parameters to update the user's profit margin.
     * The user needs to provide the new profit margin as per the request parameters.
     * The profit margin value should be a positive number between 0.01 and 100.00.
     *
     * @authenticated
     */
    public function profitUpdate(Request $request)
    {
        if ($request->profit < 0.01) return $this->unKnowError('Please enter a positive number from 0.01');
        $profit = $this->service->updateProfit($request);
        if ($profit) {
            return $this->updateResponse($profit, 'The profit margin has updated successfully');
        }
        return $this->unKnowError();
    }

    /**
     * Get Profit Margin
     *
     * The Get Profit Margin endpoint allows users to retrieve the profit margin associated with their account.
     * This endpoint provides users with information about the current profit percentage they earn from
     * selling products or services.
     *
     * This endpoint retrieves the profit margin associated with the user's account.
     * The API will respond with the current profit margin details.
     *
     * @authenticated
     */
    public function profitShow(Request $request)
    {
        return $this->apiResponse(new DropshipperResource(user()));
    }

    /**
     * Delete Dropshipper
     *
     * The Delete Dropshipper endpoint allows users to delete their dropshipper account from the system.
     * This endpoint provides a way for users to permanently remove their account and associated data.
     *
     * This endpoint deletes the dropshipper account based on the provided request parameters.
     * The user needs to provide the necessary details or confirmation to initiate the account deletion process.
     *
     * @authenticated
     */
    public function delete(Request $request)
    {
        $data = $this->service->destroy($request);
        if ($data) {
            return $this->deleteResponse(trans('auth.Done Delete Dropshipper'));
        }
        return $this->unKnowError(trans('auth.failed'));
    }

    /**
     * Check if the given password is matching the current one
     *
     * param string $password
     * return bool
     */
    public function isMatchingPassword($password)
    {
        //todo change
        return Hash::check($password, user()->password);
    }

    /**
     * Update Avatar
     *
     * The Update Avatar endpoint allows users to change their avatar or profile picture within the system.
     * This endpoint provides a way for users to update their visual representation on their profile.
     *
     * This endpoint receives the necessary parameters to update the user's avatar.
     * The user needs to provide the updated avatar image or file as per the request parameters.
     *
     * @authenticated
     */
    public function avatar(Request $request)
    {
        return $this->updateResponse($this->service->update($request, $id = null), trans('auth.Change Image'));
    }

    /**
     * Register Mega Dropshipper
     *
     */
    public function megaRegister(CreateMegaDropshipperRequest $request)
    {
        $data = $this->service->megaRegister($request);
        if ($data) {
            return $this->createResponse($data, trans('auth.Mega Dropshipper Stored Successfully!'));
        }
        return $this->unKnowError();
    }

    public function onboardingQuestionnaire(QuestionnaireRequest $request)
    {
        $data = $this->service->onboarding_questionnaire($request);
        if ($data) {
            return $this->createResponse(new DropshipperResource($data), trans('auth.change done'));
        }
        return $this->unKnowError();
    }

    public function getOnboardingQuestionnaireAnswer()
    {
        $data = $this->service->show(user()->id);
        if ($data) {
            return $this->createResponse(new DropshipperOnboardingQuestionnaireAnswerResource($data), trans('auth.change done'));
        }
        return $this->unKnowError();
    }

    /**
     * The function `createBranch` stores data for a dropshipper branch and returns a response
     * indicating success or an unknown error.
     * 
     * @param Request request The `createBranch` function is a method that handles the creation of a
     * branch based on the data provided in the `Request` object. The function calls the `store` method
     * of the `dropshipperBranchService` and then checks if the data was successfully stored. If the
     * data was stored
     * 
     * @return If the `` is successfully stored using the `store` method of the
     * `dropshipperBranchService`, a response containing a `DropshipperBranchResource` object with the
     * message 'Added successfully' will be returned using the `createResponse` method. If there is an
     * unknown error during the process, the `unKnowError` method will be called.
     */
    public function createBranch(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:50',
            'email_address' => 'required|string|max:50',
            'address' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:50',
            'code' => 'required|unique:dropshipper_branches',

        ]);

        $data = $this->dropshipperBranchService->store($request);
        if ($data) {
            return $this->createResponse(new DropshipperBranchResource($data), trans('auth.Added successfully'));
        }
        return $this->unKnowError();
    }

    /**
     * The function `updateBranch` validates and stores branch information, returning a success message
     * if successful.
     * 
     * @param Request request The `Request ` parameter in the `updateBranch` function is an
     * instance of the Illuminate\Http\Request class. It represents the HTTP request that is being made
     * to the server. In this case, it is used to validate and store the data sent in the request.
     * @param id The `id` parameter in the `updateBranch` function is typically used to identify the
     * specific branch that needs to be updated. It is commonly the unique identifier of the branch in
     * the database, such as an auto-incremented ID. This parameter helps in targeting the correct
     * branch for updating its information
     * 
     * @return If the data is successfully stored using the `store` method from the
     * `dropshipperBranchService`, a response containing the newly created `DropshipperBranchResource`
     * data along with the message 'Added successfully' is returned using the `createResponse` method.
     * If the data is not successfully stored, an 'Unknown Error' response is returned using the
     * `unKnowError` method.
     */
    public function updateBranch(Request $request, $id)
    {
        $request->validate([
            'company_name' => 'required|string|max:50',
            'email_address' => 'required|string|max:50',
            'address' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:50',
            'code' => 'required|unique:dropshipper_branches',
        ]);

        $data = $this->dropshipperBranchService->update($request,$id);
        if ($data) {
            return $this->createResponse(new DropshipperBranchResource($data), trans('auth.Added successfully'));
        }
        return $this->unKnowError();
    }

    /**
     * The function `listBranch` returns an API response with a collection of DropshipperBranch
     * resources based on the request parameters.
     * 
     * @param Request request  is an instance of the Request class, which is typically used in
     * Laravel applications to handle incoming HTTP requests and retrieve input data from forms or
     * query parameters. In this context, it is being passed to the listBranch function to retrieve
     * data related to dropshipper branches.
     * 
     * @return The `listBranch` function is returning an API response that includes a collection of
     * DropshipperBranch resources. The resources are obtained by calling the `list` method of the
     * `dropshipperBranchService` with the provided request, a boolean value of `true`, and the number
     * `12` as parameters.
     */
    public function listBranch(Request $request)
    {
        return $this->apiResponse(DropshipperBranchResource::collection($this->dropshipperBranchService->list($request, true, 12)));
    }
}
