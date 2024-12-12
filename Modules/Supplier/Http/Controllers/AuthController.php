<?php

namespace Modules\Supplier\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\Basic\Http\Controllers\BasicController;

class AuthController extends BasicController
{
    /**
     * Display a listing of the resource.
     */
    public function loginForm()
    {
        return view('supplier::auth.login');
    }

    /**
     * Show the form for creating a new resource.
     * param LoginRequest $request
     */
    //todo change function
    public function login(Request $request)
    {
        Session::flush();
        if (Auth::guard('supplier')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect(route('supplier.report.default'));
        }
        $request->session()->regenerate();
        return redirect()->back()->with(['message_false' => 'username or password is wrong']);
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
        Auth::guard('supplier')->logout();
        return redirect()->route('supplier.auth.login.form');
    }
}
