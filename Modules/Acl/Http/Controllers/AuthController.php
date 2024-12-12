<?php

namespace Modules\Acl\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\Acl\Http\Requests\Auth\LoginRequest;
use Modules\Basic\Http\Controllers\BasicController;

class AuthController extends BasicController
{
    /**
     * Display a listing of the resource.
     */
    public function loginForm()
    {
        $activeDashboard = config('dashboard.active_dashboard');
        $layout = config("dashboard.layouts.$activeDashboard");
        $loginLayout = config("dashboard.login.$activeDashboard");
        $viewData = ['layout' => $layout, 'loginLayout' => $loginLayout] ;
        return view('acl::auth.login')->with($viewData);
       
    }

    /**
     * Show the form for creating a new resource.
     * param LoginRequest $request
     */
    //todo change function
    public function login(LoginRequest $request)
    {
        Session::flush();
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            if(\auth()->user()->suspended)
            {
                return response()->json([
                    'redirect' => true,
                    'url' => route('user.edit', \auth()->user()->id)
                ]);
            }
            $request->session()->regenerate();
            return redirect(route('dashboard'));
        }
        return redirect(route('auth.login.form'))->with(['message_false' => 'username or password is wrong']);
    }

    /**
     * The function logs out the authenticated user and redirects them to the login page.
     *
     * param Request request  is an instance of the Request class which represents an HTTP
     * request. It contains information about the request such as the HTTP method, headers, and
     * parameters. In this case, it is used to handle the logout request and redirect the user to the
     * login page after logging out.
     *
     * return a redirect response to the '/login' route.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
