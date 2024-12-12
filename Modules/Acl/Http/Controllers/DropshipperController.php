<?php

namespace Modules\Acl\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Acl\Entities\Dropshipper;
use Illuminate\Support\Facades\Validator;
use Modules\Acl\Service\DropshipperService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Acl\Entities\DropshipperSetting;

/**
 * @extends BasicController
 * controller user about web function
 */
class DropshipperController extends BasicController
{
    protected $service;

    /**
     * controller user about web function
     * @required user login
     */
    public function __construct(DropshipperService $service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_dropshipper')->only('index');
        $this->middleware('permission:update_dropshipper')->only('update');
        $this->middleware('permission:delete_dropshipper')->only('destroy');
        $this->middleware('permission:extract_dropshipper')->only('extract');
        $this->service = $service;
    }

    /**
     * param Request $request
     * get all user to manage it
     */
    public function index(Request $request)
    {
        //todo change
        $rules = [
            'fromDate' => 'nullable|date',
            'toDate' => 'nullable|date|after_or_equal:fromDate',
        ];
        // Custom error messages for validation
        $messages = [
            'fromDate.date' => 'Invalid From Date format.',
            'toDate.date' => 'Invalid To Date format.',
            'toDate.after_or_equal' => 'To Date must be equal to or greater than From Date.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $dropshippers = $this->service->indexDashboard($request);
        if ($request->ajax()) {
            return view('acl::dropshipper.table')->with(['dropshippers' => $dropshippers]);
        }
        return $this->getDashboardView('acl::dropshipper.index', ['dropshippers' => $dropshippers]);
    }

    /**
     * This PHP function changes the status of phone verification and returns a JSON response indicating
     * whether the status is true or false.
     *
     * param id The ID of the phone verification record that needs to have its status changed.
     *
     * return A JSON response containing the status of the phone verification change. The status is
     * either "true" or "false" depending on whether the change was successful or not.
     */
    public function changeStatusPhoneVerification($id = null)
    {
        if (is_null($id)) {
            $id = request('id');
        }
        return response()->json(['status' => $this->service->changeStatus(
            $id,
            'isVerified'
        )->isVerified ? 'true' : 'false']);
    }

    public function changeBlocked($id = null)
    {
        if (is_null($id)) {
            $id = request('id');
        }
        return response()->json(['blocked' => $this->service->changeStatus(
            $id,
            'blocked'
        )->blocked ? 'true' : 'false']);
    }

    public function changeStatusDropshipperSetting(Request $request)
    {

        return response()->json(['dropshipper_setting' => $this->service->changeStatusDropshipperSetting(
            $request->dropshipper_id,
            $request->dropshipper_setting_id,
        ) ? 'true' : 'false']);
    }


    /**
     * The function shows a dropshipper's data and their orders/transactions.
     *
     * param id The ID of the dropshipper that needs to be shown.
     *
     * return a view with the data and orders variables passed as compact parameters. The view being
     * returned is 'acl::dropshipper.show'.
     */
    public function show($id)
    {
        $data = $this->service->show($id);
        if (!$data)
            abort(404);
        // Get dropshipper orders/transactions
        $orders = $data->order()->orderByDesc('id')->paginate(15);
        $branches = $data->DropshipperBranch;
        $transactions = $data->transaction()->orderByDesc('id')->paginate(15);
        $dropshipperSetting =  DropshipperSetting::get();

        return $this->getDashboardView('acl::dropshipper.show', compact('data', 'orders', 'branches', 'transactions', 'dropshipperSetting'));
    }

    public function search(Request $request)
    {
        return $this->service->list($request);
    }

    public function updateFeature(Request $request)
    {
        $dropshipper = Dropshipper::findOrFail($request->id);

        // Update percentage and enable the feature
        $dropshipper->extra_product_feature_enabled = true;
        $dropshipper->product_price_percentage = $request->percentage;
        $dropshipper->save();

        return response()->json(['message' => 'Feature and percentage updated successfully']);
    }

    public function clearFeature(Request $request)
    {
        $dropshipper = Dropshipper::findOrFail($request->id);

        // Clear the fields and disable the feature
        $dropshipper->extra_product_feature_enabled = false;
        $dropshipper->product_price_percentage = 0;
        $dropshipper->save();

        return response()->json(['message' => 'Feature and percentage cleared']);
    }

    // update feature and percentage
    // validate data first
    // redirect back
    public function updateFeatureForm(Request $request, $id)
    {
        $dropshipper = Dropshipper::findOrFail($id);

        if ($request->percentage == 0 || $request->percentage == null || !isset($request->percentage)) {
            // Clear the fields and disable the feature
            $dropshipper->extra_product_feature_enabled = false;
            $dropshipper->product_price_percentage = 0;
            $dropshipper->save();

            return redirect()->back()->with('success', 'Feature and percentage cleared');
        }

        // Update percentage and enable the feature
        $dropshipper->extra_product_feature_enabled = true;
        $dropshipper->product_price_percentage = $request->percentage;
        $dropshipper->save();

        return redirect()->back()->with('success', 'Feature and percentage updated successfully');
    }

    public function orders(Request $request)
    {
        $data = $this->service->show($request->dropshipper_id);
        $orders = $data->order()->orderByDesc('id')->paginate(15);


        // Fetch paginated items
        // $items = Item::paginate(5); // Customize pagination limit

        if ($request->ajax()) {
            // Return paginated data only when an AJAX request is detected
            return view('acl::dropshipper.orders_table', compact('orders'))->render();
        }
    }

    public function transactions(Request $request)
    {
        $data = $this->service->show($request->dropshipper_id);
        $transactions = $data->transaction()->orderByDesc('id')->paginate(15);


        // Fetch paginated items
        // $items = Item::paginate(5); // Customize pagination limit

        if ($request->ajax()) {
            // Return paginated data only when an AJAX request is detected
            return view('acl::dropshipper.transactions_table', compact('transactions'))->render();
        }
    }

    public function updateMaxDiscount(Request $request, int $id)
    {
        $request->validate([
            'max_discount' => 'required|integer|min:0|max:100',
        ]);
        $dropshipper = Dropshipper::findOrFail($id);
        $dropshipper->max_discount = $request->max_discount;
        $dropshipper->save();
        return response()->json(['success' => true, 'message' => 'Max discount updated successfully.']);
    }
}
