<?php

namespace Modules\Basic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//todo change
class TableHandlerController extends Controller
{
    /**
     * This function sets the length of a table and stores it in the session.
     * 
     * param Request request  is an instance of the Request class which contains the data sent
     * by the client in the HTTP request. It is used to retrieve input data, validate it, and perform
     * other operations related to the request. In this function, it is used to retrieve the
     * 'table_length' input value from the request
     * 
     * return a redirect back to the previous page.
     */
    public function setTableLength(Request $request)
    {
        $request->validate([
            'table_length' => 'required|numeric'
        ]);
        if (isset($request->table_length) && $request->table_length  != null) {
            session()->put('table_length', $request->table_length);
        }
        if (session()->has('table_length') == false) {
            session()->put('table_length', config('app.pagination_pages'));
        }
        return redirect()->back();
    }
}
