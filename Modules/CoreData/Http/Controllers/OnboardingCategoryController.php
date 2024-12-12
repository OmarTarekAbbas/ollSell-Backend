<?php

namespace Modules\CoreData\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Http\Requests\OnboardingCategory\EditRequest;
use Modules\CoreData\Http\Requests\OnboardingCategory\CreateRequest;
use Modules\CoreData\Service\OnboardingCategoryService;

class OnboardingCategoryController extends BasicController
{
    protected $service;

    /**
     * This function constructs a CategoryService object and sets middleware permissions for various
     * category-related actions.
     *
     * param CategoryService Service The  parameter is an instance of the CategoryService
     * class, which is likely responsible for handling business logic related to categories in the
     * application. It is being injected into the constructor using dependency injection.
     */
    public function __construct(OnboardingCategoryService $Service)
    {
        $this->middleware('auth');
        $this->middleware('permission:view_onboarding_categories')->only('index');
        $this->middleware('permission:create_onboarding_categories')->only(['create','store']);
        $this->middleware('permission:update_onboarding_categories')->only(['edit','update']);
        $this->middleware('permission:delete_onboarding_categories')->only('destroy');
        $this->service = $Service;
    }

    public function index(Request $request)
    {
        $datas = $this->service->index($request);

        if ($request->ajax()) {
            return view('coredata::onboarding_category.table')->with(['datas' => $datas]);
        }

        return $this->getDashboardView('coredata::onboarding_category.index', compact('datas'));
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
        return $this->getDashboardView('coredata::onboarding_category.create');
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
        return $this->getDashboardView('coredata::onboarding_category.show', compact('data'));
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
            return redirect(route('onboarding_category.index'))->with('Done');
        }
        return redirect(route('onboarding_category.create'))->with('problem');
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
        $data = $this->service->show($id);
        return $this->getDashboardView('coredata::onboarding_category.edit', compact('data'));
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
            return redirect(route('onboarding_category.index'))->with("message", 'Done');
        }
        return redirect(route('onboarding_category.edit', $id))->with('problem');
    }


}
