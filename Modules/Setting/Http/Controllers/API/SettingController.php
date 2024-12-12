<?php

namespace Modules\Setting\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Basic\Traits\ApiResponseTrait;
use Modules\Setting\Service\SettingService;

class SettingController extends Controller
{
    public $service;
    use ApiResponseTrait;

    /**
     * This is a PHP constructor function that takes an instance of the SettingService class as a
     * parameter and assigns it to a property of the current object.
     *
     * param SettingService service The parameter "service" is an instance of the SettingService class
     * that is being injected into the constructor of the current class. This is a common practice in
     * dependency injection, where the dependencies of a class are passed in as constructor parameters
     * rather than being instantiated within the class itself. This allows for better dec
     */
    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    /**
     * Show the form for editing the specified resource.
     * param int $id
     * return Renderable
     */
    public function list(Request $request)
    {
        $data = $this->service->getDynamicIntegration();
        return $this->apiResponse($data, '', 200, []);
    }
    //todo change
    public function shippingFees(Request $request)
    {
        $shippingFees = setting('shipping_fee') ?? 25;
        $currency = 'sar';
        $formattedFeesEn = $shippingFees . ' ' . $currency;
        $formattedFeesAr = $shippingFees . ' ريال';

        $responseData = [
            'shipping_fees' => (int) $shippingFees,
            'currency' => $currency,
            'formatted_shipping_fees_en' => $formattedFeesEn,
            'formatted_shipping_fees_ar' => $formattedFeesAr,
        ];

        return $this->apiResponse($responseData, '', 200, []);
    }
}
