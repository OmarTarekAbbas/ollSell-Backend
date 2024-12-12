<?php

namespace Modules\Acl\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Acl\Service\UserService;
use Modules\Acl\Http\Requests\User\EditRequest;
use Modules\Acl\Http\Requests\User\CreateRequest;
use Modules\Basic\Http\Controllers\BasicController;

/**
 * @extends BasicController
 * controller user about web function
 */
class UserController extends BasicController
{
    protected $service;

    /**
     * @extends BasicController
     * controller user about web function
     * @required user login
     */
    public function __construct(UserService $service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_users')->only('index');
        $this->middleware('permission:create_users')->only('create');
        $this->middleware('permission:create_users')->only('store');
        $this->middleware('permission:update_users')->only('edit');
        $this->middleware('permission:update_users')->only('update');
        $this->middleware('permission:delete_users')->only('destroy');
        $this->service = $service;
    }

    /**
     * param Request $request
     * get all user to manage it
     */
    public function index(Request $request)
    {
        $tableLength = session('table_length') ?? config('app.pagination_pages');
        $moreConditionForFirstLevel = [];
        //todo change
        if (isset($request->search) && $request->search != null) {
            $moreConditionForFirstLevel += ['orWhere' => ['name' => ['LIKE', '%' . $request->search . '%'], 'email' => ['LIKE', '%' . $request->search . '%']]];
        }

        $users = $this->service->findBy(
            $request,
            withRelations: ['role.role'],
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            pagination: true,
            perPage: $tableLength
        );
        if ($request->ajax()) {
            return view('acl::users.table')->with(['users' => $users]);
        }

        return $this->getDashboardView('acl::users.index', ['users' => $users]);
    }

    public function indexForList(Request $request)
    {
        $operators = $this->service->userOrderList($request);
        return response()->json($operators);
    }

    /**
     * This PHP function returns a view for creating a user with a list of available roles.
     *
     * return a view named 'acl::users.create' with a variable named 'role' that contains the list of
     * roles obtained from the 'roleList' method of the 'service' object.
     */
    public function create()
    {
        $role = $this->service->roleList();
        return $this->getDashboardView('acl::users.create', compact('role'));
    }

    /**
     * This PHP function stores data from a CreateRequest and redirects to the user index or create
     * page depending on success.
     *
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form or an API request, and it also includes validation rules and messages
     * for the data. The  parameter is passed to the
     *
     * return If the `` variable is truthy, the function will return a redirect to the
     * `user.index` route with a flash message "Done". Otherwise, it will return a redirect to the
     * `user.create` route with a flash message "problem".
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('user.index'))->with('Done');
        }
        return redirect(route('user.create'))->with('problem');
    }

    /**
     * This PHP function returns a view for editing a user's data and role.
     *
     * param id The parameter "id" is the unique identifier of the user that needs to be edited. It
     * is used to retrieve the user's data from the database and display it on the edit form.
     *
     * return a view named 'acl::users.edit' with the data and role variables passed as compact
     * parameters.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        $role = $this->service->roleList();
        return $this->getDashboardView('acl::users.edit', compact('data', 'role'));
    }

    /**
     * This function updates user data and redirects to the user edit page with a success or error
     * message.
     *
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating user data. It is used to
     * validate the incoming request data before processing it further.
     * param id  is a parameter that represents the unique identifier of the user that needs to be
     * updated. It is used to locate the specific user record in the database and update its
     * information.
     *
     * return If the `` variable is truthy, the function will return a redirect to the
     * `user.edit` route with a success message. Otherwise, it will return a redirect to the same route
     * with a problem message.
     */
    public function update(EditRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('user.edit', $id))->with("message", 'Done');
        }
        return redirect(route('user.edit', $id))->with('problem');
    }

    /**
     * This PHP function shows data for a user with a specific ID.
     *
     * param id  is a parameter that represents the unique identifier of the user whose data is
     * being requested. It is used to retrieve the user's data from the database or any other data
     * source.
     *
     * return a view named 'acl::users.show' with the data passed as a compact variable.
     */
    public function show($id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('acl::users.show', compact('data'));
    }

    /**
     * The function "changePassword" takes a request and an ID as parameters, retrieves data using the
     * ID, and returns a view with the retrieved data.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request such as the request
     * method, headers, and input data.
     * param id The "id" parameter is the unique identifier of the user whose password needs to be
     * changed.
     *
     * return the dashboard view for changing a user's password, passing the user data as a compact
     * variable.
     */
    public function changePassword(Request $request, $id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('acl::users.changePassword', compact('data'));
    }

    /**
     * The function updates the password if the old password provided by the user matches the current
     * password.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve data from the HTTP request. It contains information such as the request method,
     * headers, and input data.
     *
     * return If the old password is wrong, the method will return the message "The old password is
     * wrong" using the `apiValidation` method. If the old password is correct, the method will call
     * the `update` method of the `service` object and return its result.
     */
    public function updatePassword(Request $request)
    {
        //todo change
        $request->validate([
            'password' => 'required|min:4|confirmed',
        ]);
        $this->service->update($request, $request->id);
        return redirect(route('user.index'))->with('Done');
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
}
