<?php

namespace Modules\Subscription\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Modules\Subscription\Service\FeatureService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Subscription\Http\Requests\Feature\EditRequest;
use Modules\Subscription\Http\Requests\Feature\CreateRequest;
class FeatureController extends BasicController
{
    protected FeatureService $service;

    /**
     * The function is a constructor that initializes the class with a FeatureService object and
     * applies the 'auth' middleware.
     * 
     * param FeatureService Service The parameter `` is an instance of the `FeatureService`
     * class. It is being injected into the constructor of the current class.
     */
    public function __construct(FeatureService $Service)
    {
        $this->middleware('auth');
        $this->middleware('permission:view_features')->only('index');
        $this->middleware('permission:create_features')->only('create');
        $this->middleware('permission:create_features')->only('store');
        $this->middleware('permission:update_features')->only('edit');
        $this->middleware('permission:update_features')->only('update');
        $this->middleware('permission:delete_features')->only('destroy');
        $this->service = $Service;
    }

    /**
     * @throws Exception
     */
    public function index(Request $request)
    {
        $features = $this->service->list($request, true);

        if ($request->ajax()) {
            return $this->getDashboardView('subscription::feature.table',
                compact('features', 'request')
            );
        }

        return $this->getDashboardView('subscription::feature.index', compact('features', 'request'));
    }

    /**
     * The function creates a new feature by listing the features and returning the dashboard view with the
     * feature data.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request such as the HTTP method,
     * headers, and request parameters.
     * 
     * return the dashboard view for creating a subscription feature, with the feature data passed as a
     * parameter.
     */
    public function create(Request $request)
    {
        $feature = $this->service->list($request);
        return $this->getDashboardView('subscription::feature.create', compact('feature'));
    }

    /**
     * The function stores data and redirects the user to the index page if successful, or back to the
     * create page if there is a problem.
     * 
     * param CreateRequest request The  parameter is an instance of the CreateRequest class.
     * It is used to retrieve the input data from the user.
     * 
     * return If the `` variable is truthy, the function will return a redirect to the
     * `feature.index` route with a flash message "Done". Otherwise, it will return a redirect to the
     * `feature.create` route with a flash message "problem".
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('feature.index'))->with('Done');
        }
        return redirect(route('feature.create'))->with('problem');
    }

    /**
     * The edit function retrieves data for a specific subscription feature and returns the view for
     * editing that feature.
     * 
     * param id The parameter "id" is the identifier of the subscription feature that needs to be edited.
     * It is used to retrieve the data of the feature from the service.
     * 
     * return the dashboard view for editing a subscription feature, passing the data of the feature with
     * the specified ID.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('subscription::feature.edit', compact('data'));
    }

    /**
     * This function updates a record and redirects the user to the index page with a success message
     * if the update is successful, otherwise it redirects the user back to the edit page with a
     * problem message.
     * 
     * param EditRequest request The  parameter is an instance of the EditRequest class, which
     * is used to validate and retrieve the data submitted in the edit form.
     * param id The id parameter is the identifier of the record that needs to be updated. It is used
     * to locate the specific record in the database and update its information.
     * 
     * return If the `` variable is truthy, the function will return a redirect response to the
     * `feature.index` route with a success message. Otherwise, it will return a redirect response to the
     * `feature.edit` route with no message.
     */
    public function update(EditRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('feature.index'))->with("message", 'Done');
        }
        return redirect(route('feature.edit', $id))->with('problem');
    }
}
