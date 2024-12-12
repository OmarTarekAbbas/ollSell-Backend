<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Setting\Service\SettingService;


class SettingController extends BasicController
{
    public $service;

    /**
     * This is a constructor function that sets middleware and initializes a SettingService object.
     *
     * param SettingService service The `` parameter is an instance of the `SettingService`
     * class that is injected into the constructor using dependency injection. This allows the
     * controller to access the methods and functionality provided by the `SettingService` class.
     */
    public function __construct(SettingService $service)
    {
        $this->middleware('permission:update_settings')->only(['editIntegration', 'edit', 'update']);
        $this->service = $service;
    }

    /**
     * Show the form for editing the specified resource.
     * param int $id
     */
    public function list(Request $request)
    {
        return $this->getDashboardView('setting::settings.list');
    }

    public function basic(Request $request)
    {
        return $this->getDashboardView('setting::settings.basic');
    }

    /**
     * Show the form for editing the specified resource.
     * param int $id
     */
    public function editIntegration(Request $request)
    {
        return $this->getDashboardView('setting::settings.api_integration');
    }

    /**
     * Show the form for editing the specified resource.
     * param int $id
     */
    public function edit(Request $request)
    {
        return $this->getDashboardView('setting::settings.edit');
    }

    /**
     * Update the specified resource in storage.
     * param Request $request
     * param int $id
     */
    public function update(Request $request)
    {
        $this->service->update($request);
        session()->flash('success', trans('admin.setting-edit-message'));
        return redirect()->back();
    }

    public function shipping(Request $request)
    {
        return $this->getDashboardView('setting::settings.shipping');
    }

    public function updateShipping(Request $request)
    {
        $this->service->update($request);
        session()->flash('success', trans('admin.setting-edit-message'));
        return redirect()->back();
    }

    /**
     * This function sets a dynamic integration for an API and redirects back with a success message.
     *
     * param Request request  is an instance of the Request class which represents an HTTP
     * request. It contains information about the request such as the HTTP method, headers, and any
     * data sent in the request body. In this function,  is used to retrieve data sent from a
     * form submission.
     *
     * return a redirect back to the previous page after setting a dynamic integration based on the
     * request data and flashing a success message to the session.
     */
    public function setApiIntegration(Request $request)
    {
        $this->service->setDynamicIntegration($request);
        session()->flash('success', trans('admin.setting-edit-message'));
        return redirect()->back();
    }
    /**
     * Show the form for editing the specified resource.
     * param int $id
     */
    public function editSallaIntegration(Request $request)
    {
        return $this->getDashboardView('setting::settings.salla_integration');
    }

    public function setSallaIntegration(Request $request)
    {
        $this->service->setDynamicSallaIntegration($request);
        session()->flash('success', trans('admin.setting-edit-message'));
        return redirect()->back();
    }


    public function editEmailSetting(Request $request)
    {
        return $this->getDashboardView('setting::settings.email_setting');
    }

    public function setEmailSetting(Request $request)
    {
        $this->service->setDynamicSallaIntegration($request);
        session()->flash('success', trans('admin.setting-edit-message'));
        return redirect()->back();
    }

    public function editOllopsSetting(Request $request)
    {
        return $this->getDashboardView('setting::settings.ollops_setting');
    }

    public function setOllopsSetting(Request $request)
    {
        $this->service->setDynamicSallaIntegration($request);
        session()->flash('success', trans('admin.setting-edit-message'));
        return redirect()->back();
    }

    public function editOrderSetting(Request $request)
    {
        return $this->getDashboardView('setting::settings.order_setting');
    }

    public function setOrderSetting(Request $request)
    {
        $this->service->setOrderSetting($request);
        session()->flash('success', trans('admin.setting-edit-message'));
        return redirect()->back();
    }

    public function editDropshipperSetting(Request $request)
    {
        return $this->getDashboardView('setting::settings.dropshipper_setting');
    }

    public function setDropshipperSetting(Request $request)
    {
        $this->service->setDropshipperSetting($request);
        session()->flash('success', trans('admin.setting-edit-message'));
        return redirect()->back();
    }

    public function editAymakanIntegration(Request $request)
    {
        return $this->getDashboardView('setting::settings.aymakan_integration');
    }

    public function setAymakanIntegration(Request $request)
    {
        $this->service->setDynamicSallaIntegration($request);
        session()->flash('success', trans('admin.setting-edit-message'));
        return redirect()->back();
    }

    /**
     * The function "listPaySetting" returns a dashboard view for the pay setting list.
     *
     * @param Request request The `listPaySetting` function is a method that returns a view for listing
     * payment settings. The function takes a `Request` object as a parameter named ``. The
     * `Request` object is typically used to retrieve input data from the HTTP request.
     *
     * @return The function `listPaySetting` is returning a view named `listPay_setting` from the
     * `setting::settings` namespace.
     */
    public function listPaySetting(Request $request)
    {
        return $this->getDashboardView('setting::settings.listPay_setting');
    }

    /**
     * The `paySetting` function sets dropshipper settings and redirects back with a success message.
     *
     * @param Request request The `Request ` parameter in the `paySetting` function is an
     * instance of the `Illuminate\Http\Request` class in Laravel. It represents an HTTP request that
     * contains all the data sent by the client.
     *
     * @return The function `paySetting` is returning a redirect back to the previous page after
     * setting the dropshipper setting and flashing a success message to the session.
     */
    public function paySetting(Request $request)
    {
        $this->service->setDropshipperSetting($request);
        session()->flash('success', trans('admin.setting-edit-message'));
        return redirect()->back();
    }
}
