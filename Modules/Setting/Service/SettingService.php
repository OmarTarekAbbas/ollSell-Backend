<?php

namespace Modules\Setting\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\Basic\Traits\MediaTrait;
use Modules\Setting\Entities\Setting;

class SettingService extends BasicService
{
    use MediaTrait;

    /**
     * This PHP function updates settings data and saves it to the database.
     *
     * param Request request  is an instance of the Illuminate\Http\Request class which
     * represents an HTTP request. It contains information about the request such as the HTTP method,
     * headers, and input data. In this function, it is used to retrieve input data from the request
     * and check if a logo file was uploaded.
     * param id The  parameter is an optional parameter that represents the ID of the setting that
     * needs to be updated. If it is not provided, then the function will update all the settings.
     *
     * return The `save()` method of the `Setting` model is being called and its return value is being
     * returned. The `save()` method saves the changes made to the model instance in the database and
     * returns a boolean value indicating whether the save was successful or not.
     */
    public function update(Request $request, $id = null)
    {
        $data = $request->except('_token', 'logo');
        if ($request->has('logo')) {
            $data['logo'] = $this->settingImage('settings', $request->logo);
        }
        foreach ($data as $index => $value) {
            Setting::where('key', $index)->update(['value' => $value]);
        }
        return true;
    }

    /**
     * This function sets dynamic integration settings and saves them to the database.
     *
     * param Request request  is an instance of the Request class which contains the data sent
     * in the HTTP request. It can contain data from the URL parameters, form data, headers, cookies,
     * and more. In this function, it is used to retrieve data sent from a form submission.
     */
    public function setDynamicIntegration(Request $request)
    {
        if ($request->has('enable_dynamic_integration') && $request->enable_dynamic_integration == 'on') {
            Setting::where('key', 'enable_dynamic_integration')->update(['value' => true]);
        } else {
            Setting::where('key', 'enable_dynamic_integration')->update(['value' => true]);
        }

        $data = $request->except(['_token', 'enable_dynamic_integration']);
        foreach ($data as $index => $value) {
            Setting::where('key', $index)->update(['value' => $value]);
        }
        return true;
    }

    public function setDropshipperSetting(Request $request)
    {
        if ($request->has('enable_salla_integration') && $request->enable_salla_integration == 'on') {
            Setting::where('key', 'enable_salla_integration')->update(['value' => true]);
        } else {
            Setting::where('key', 'enable_salla_integration')->update(['value' => false]);
        }

        $data = $request->except(['_token', 'enable_salla_integration']);
        foreach ($data as $index => $value) {
            Setting::where('key', $index)->update(['value' => $value]);
        }
        return true;
    }

    public function setOrderSetting(Request $request)
    {
        if ($request->has('enable_stock_quantity') && $request->enable_stock_quantity == 'on') {
            Setting::where('key', 'enable_stock_quantity')->update(['value' => true]);
        } else {
            Setting::where('key', 'enable_stock_quantity')->update(['value' => false]);
        }

        $data = $request->except(['_token', 'enable_stock_quantity']);
        foreach ($data as $index => $value) {
            Setting::where('key', $index)->update(['value' => $value]);
        }
        return true;
    }

    public function setDynamicSallaIntegration(Request $request)
    {
        $data = $request->except(['_token']);
        foreach ($data as $index => $value) {
            Setting::where('key', $index)->update(['value' => $value]);
        }
        return true;
    }
    /**
     * The function returns an array of settings related to dynamic integration in PHP.
     *
     * return An array containing the values of the 'enable_dynamic_integration', 'target_market_url',
     * and 'product_url' settings.
     */
    public function getDynamicIntegration()
    {
        $data = [
            'CASH_ON_DELIVERY' => (setting('CASH_ON_DELIVERY') == '1' ? true : false),
            'ONLINE_METHOD' => (setting('ONLINE_METHOD') == '1' ? true : false),
            'WALLET_METHOD' => (setting('WALLET_METHOD') == '1' ? true : false),
            'return_policy_ar' => setting('return_policy_ar'),
            'return_policy_en' => setting('return_policy_en'),
            'logo' => setting('logo'),
            'version' => setting('version'),
            'bundle_discount' => (float) setting('bundle_discount'),
            'project_name' => setting('project_name'),
            'vat_suppler' => (float)setting('vat_suppler'),
            'vat_profit' => (float)setting('vat_profit'),
            'vat_product' => (float)setting('vat_product'),
            'validation_type' => setting('validation_type'),
            'shipping_fee' => (float)setting('shipping_fee'),
            'product_url' => setting('product_url')
        ];
        return $data;
    }

    /**
     * The function `paySetting` updates payment settings based on the data received in the request.
     * 
     * @param Request request The `paySetting` function takes a `Request` object as a parameter. This
     * object contains the data sent with the HTTP request, such as form input values. The function
     * then extracts the data from the request object, excluding the `_token` field. It iterates over
     * the extracted data and updates
     * 
     * @return The `paySetting` function is returning a boolean value `true`.
     */
    public function paySetting(Request $request)
    {
        $data = $request->except(['_token']);
        foreach ($data as $index => $value) {
            Setting::where('key', $index)->update(['value' => $value]);
        }
        return true;
    }
}
