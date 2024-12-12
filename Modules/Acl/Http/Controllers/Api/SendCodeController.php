<?php

namespace Modules\Acl\Http\Controllers\Api;

use Modules\Acl\Http\Requests\Dropshipper\Api\CreateStepTwoRequest;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Acl\Service\DropshipperService;

/**
 * @group Authentication
 *
 */
class SendCodeController extends BasicController
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
     * Step Two Registration
     *
     * The Step Two Registration endpoint allows users to proceed to the second step of the registration
     * process by providing the necessary information. This endpoint handles the submission of the
     * required details for the second step of registration, including verifying the provided verification code.
     *
     */
    public function stepTwoRegister(CreateStepTwoRequest $request)
    {
        $data = $this->service->storeStepTwo($request);
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
}
