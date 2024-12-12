<?php

namespace Modules\StoreIntegrations\Http\Controllers\Salla;

use Illuminate\Http\Request;
use Modules\StoreIntegrations\Actions\StoreAuthorize;

class AuthController
{
    public function handleCallback(Request $request)
    {
        /** 
         * 1. Initialize StoreAuthorize action to process the Salla response.
         * This action should handle the Easy Mode data provided by Salla.
         */
        $storeAuthorize = new StoreAuthorize();
        
        // 2. Pass data received from Salla to StoreAuthorize
        $storeAuthorize->data = $request->all();
        
        // 3. Call handle() to process and save the merchant's data
        $storeAuthorize->handle();

        return response()->json(['message' => 'Successfully connected to Salla!'], 200);
    }

}
