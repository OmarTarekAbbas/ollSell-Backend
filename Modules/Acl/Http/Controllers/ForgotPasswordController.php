<?php

namespace Modules\Acl\Http\Controllers;

use Modules\Acl\Service\ForgotPasswordService;
use Modules\Acl\Http\Requests\Auth\ResetPasswordRequest;
use Modules\Acl\Http\Requests\Auth\ResetPasswordFormRequest;
use Modules\Basic\Http\Controllers\BasicController;

class ForgotPasswordController extends BasicController
{
    protected $service;

    /**
     * This is a constructor function that injects an instance of the ForgotPasswordService class into
     * the current class.
     *
     * param ForgotPasswordService Service The parameter "Service" is an instance of the
     * "ForgotPasswordService" class that is being injected into the constructor of another class. This
     * is a common practice in object-oriented programming and is known as dependency injection. By
     * injecting the service class into the constructor, the class can use the methods and properties
     */
    public function __construct(ForgotPasswordService $Service)
    {
        $this->service = $Service;
    }

    /**
     * Display a listing of the resource.
     */
    public function resetPasswordForm()
    {
        return $this->getDashboardView('acl::auth.reset_password');
    }

    /**
     * Display a listing of the resource.
     */
    //todo change function
    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->service->sendLink($request);
    }

    /**
     * This PHP function returns a view for resetting a password with an email and token.
     *
     * param token The token is a unique string that is generated when a user requests to reset their
     * password. This token is sent to the user's email address and is used to verify the user's
     * identity when they reset their password. The token is usually valid for a limited time period
     * and expires after that time.
     *
     * return a view with the path 'acl::auth.new_password' and an array of data containing the email
     * and token values.
     */
    public function showResetPasswordForm($token)
    {
        return $this->getDashboardView(
            'acl::auth.new_password',
            [
                'email' => \request()->all()['email'],
                'token' => $token
            ]
        );
    }

    /**
     * This function submits a reset password form and redirects the user to the login page with a
     * success message if the password is reset successfully, or returns an error message if the token
     * is invalid.
     *
     * param ResetPasswordForm request The  parameter is an instance of the ResetPasswordForm
     * class, which is a custom form request class that validates the input data for resetting a user's
     * password. It contains the user's email, new password, and password confirmation. The
     * submitResetPasswordForm() function uses this request object to call
     *
     * return If the password reset is successful, the function will return a redirect to the login
     * page with a success message. If the password reset fails due to an invalid token, the function
     * will return back to the previous page with an error message and the input data will be retained.
     */
    public function submitResetPasswordForm(ResetPasswordFormRequest $request)
    {
        $reset = $this->service->ResetPassword($request);
        if($reset)
        {
            return redirect('/login')->with('message', 'Your password has been changed!');
        }else
        {
            return back()->withInput()->with('error', 'Invalid token!');
        }
    }
}
