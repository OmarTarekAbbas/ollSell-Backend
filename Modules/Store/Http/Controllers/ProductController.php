<?php

namespace Modules\Store\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Store\Entities\UserDomain;
//todo change
class ProductController extends Controller
{
    private $client;
    private $userDomain;

    public function __construct()
    {
        $this->middleware('auth:dropshipper');
        $this->userDomain = UserDomain::where('dropshipper_id', Auth::guard('dropshipper')->user()->id ?? 0)->first();
        $username =  $this->userDomain->username ?? null;
        $this->client = new Client(['base_uri' => 'https://'. $username .'.store.ollsell.olltek.com/']);
    }

    public function export(Request $request)
    {
        $payload = [
            'dropshipper_id' => $this->userDomain->dropshipper_id,
            'products' => $request->products,
        ];

        try {
            $response = $this->client->post('api/company/store/products', [
                'form_params' => $payload
            ]);

            // Process the response as needed
            $responseData = json_decode($response->getBody(), true);

            // Return a response or perform any other actions
            return response()->json($responseData);
        } catch (\Exception $e) {
            // Handle any errors or exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
