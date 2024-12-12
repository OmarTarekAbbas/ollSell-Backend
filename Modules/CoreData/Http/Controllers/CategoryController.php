<?php

namespace Modules\CoreData\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Modules\CoreData\Entities\Category;
use Modules\CoreData\Service\CategoryService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Http\Requests\Category\EditRequest;
use Modules\CoreData\Http\Requests\Category\CreateRequest;

class CategoryController extends BasicController
{
    protected CategoryService $service;

    /**
     * This function constructs a CategoryService object and sets middleware permissions for various
     * category-related actions.
     *
     * param CategoryService Service The  parameter is an instance of the CategoryService
     * class, which is likely responsible for handling business logic related to categories in the
     * application. It is being injected into the constructor using dependency injection.
     */
    public function __construct(CategoryService $Service)
    {
        $this->middleware('auth');
        $this->middleware('permission:view_categories')->only('index');
        $this->middleware('permission:create_categories')->only(['create','store']);
        $this->middleware('permission:update_categories')->only(['edit','update']);
        $this->middleware('permission:delete_categories')->only('destroy');
        $this->service = $Service;
    }

    public function index(Request $request)
    {
        $categories = $this->service->index($request);

        if ($request->ajax()) {
            return view('coredata::category.table')->with(['categories' => $categories]);
        }

        return $this->getDashboardView('coredata::category.index', compact('categories'));
    }

    /**
     * This PHP function creates a category with an active status and returns a view with a list of
     * categories.
     *
     * param Request request  is an instance of the Request class, which contains the data
     * sent by the client in the HTTP request. It can contain data from the URL parameters, form data,
     * headers, cookies, and more. In this case, the  object is being used to merge a new
     * key-value pair into
     *
     * return a view called "coredata::category.create" with a variable called "category" passed to
     * it. The "category" variable is obtained by calling the "list" method of the "service" object
     * with the "request" object passed to the function.
     */
    public function create(Request $request)
    {
        //todo change
        $category = $this->service->list($request);
        $category = Category::where('isApproved', 1)->get();

        return $this->getDashboardView('coredata::category.create', compact('category'));
    }


    /**
     * This PHP function returns a view for creating a new warehouse with categories and target markets
     * as options.
     *
     * return a view for creating a new warehouse with the categories and target markets passed as
     * variables.
     */
    public function show($id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('coredata::category.show', compact('data'));
    }

    /**
     * This function stores data from a CreateRequest object and redirects to the category index page
     * with a success or error message.
     *
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form when creating a new category. This data is validated by the rules
     * defined in the CreateRequest class before being passed to the store
     *
     * return If the `` variable is truthy, the function will return a redirect to the
     * `category.index` route with a flash message "Done". Otherwise, it will return a redirect to the
     * `category.create` route with a flash message "problem".
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('category.index'))->with('Done');
        }
        return redirect(route('category.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data for a category with a specific ID and returns a view for
     * editing that category.
     *
     * param id The parameter "id" is the identifier of the category that needs to be edited. It is
     * used to retrieve the category data from the database using the "show" method of the "service"
     * object. The retrieved data is then passed to the view along with the view name using the
     * "getDashboard
     *
     * return a view named 'coredata::category.edit' with the data of a category with the given .
     */
    public function edit($id)
    {
        //todo change
        $data = $this->service->show($id);
        $category = Category::where('id', '!=', $id)
            ->where(function ($query) use ($id) {
                $query->where('parent_id', '!=', $id)
                    ->orWhereNull('parent_id');
            })
            ->where('isApproved', 1)
            ->get();

        return $this->getDashboardView('coredata::category.edit', compact('data', 'category'));
    }

    /**
     * This PHP function updates a category and redirects the user to the category index page with a
     * success message or back to the edit page with an error message.
     *
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a category. It is used to
     * validate the incoming request data before processing it further.
     * param id  is a parameter that represents the ID of the category that needs to be updated. It
     * is used to identify the specific category record in the database that needs to be updated.
     *
     * return If the update is successful, the function will return a redirect to the index page with
     * a success message. If the update fails, the function will return a redirect to the edit page
     * with a problem message.
     */
    public function update(EditRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('category.index'))->with("message", 'Done');
        }
        return redirect(route('category.edit', $id))->with('problem');
    }

    /**
     * The function "changeCommission" updates a record in the database and redirects the user to the
     * category index page with a success message, or back to the category edit page with an error
     * message.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * contains the data and information from the HTTP request made to the server. It is used to
     * retrieve input data, headers, cookies, and other request-related information.
     * param id The  parameter is the identifier of the category that needs to be updated. It is
     * used to locate the specific category in the database and make changes to its commission.
     *
     * return a redirect response. If the data is successfully updated, it will redirect to the
     * 'category.index' route with a success message. If there is a problem with the update, it will
     * redirect to the 'category.edit' route with no specific message.
     */
    public function changeCommission(Request $request)
    {
        //todo change
        $request->validate([
            'commission' => 'required|numeric|min:0|max:1000'
        ]);
        $data = Category::find($request->category_id);
        $data->commission = $request->commission;

        if ($data->save()) {
            return redirect(route('category.index'))->with("message", 'Done');
        }
        return redirect(route('category.edit', $request->category_id))->with('problem');
    }

}
