<?php

namespace Modules\Store\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Store\Entities\UserDomain;
//todo change
class StoreController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->middleware('auth:dropshipper');

        $this->client = new Client(['base_uri' => 'https://store.ollsell.olltek.com/']);
    }

    public function stepOne(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $payload = [
            'email' => $request->input('email'),
        ];

        try {
            $response = $this->client->post('company/validate/step-one', [
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

    public function stepThree(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:user_domain'
        ]);

        $payload = [
            'name' => $request->input('name'),
            'username' => $request->input('username')
        ];

        try {
            $response = $this->client->post('company/validate/step-three', [
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

    public function register(Request $request)
    {
        $request->validate([
            'email'         => 'required|email|max:191|unique:user_domain',
            'password'      => 'required|string|confirmed|min:6',
            'first_name'    => 'required|string|max:191',
            'last_name'     => 'nullable|string|max:191',
            'phone'         => 'required',
            'username'      => 'required|alpha_num|min:3|max:64|unique:user_domain',
            'name'          => 'required|string|max:191'
        ]);

        $payload = [
            'email'         => $request->input('email'),
            'password'      => $request->input('password'),
            'first_name'    => $request->input('first_name'),
            'last_name'     => $request->input('last_name'),
            'phone'         => $request->input('phone'),
            'username'      => $request->input('username'),
            'name'          => $request->input('name'),
            'user_id'       => auth()->id()
        ];

        try {
            $response = $this->client->post('company/register', [
                'form_params' => $payload
            ]);

            // Process the response as needed
            $responseData = json_decode($response->getBody(), true);

            UserDomain::create([
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'username' => $request->username,
                'name' => $request->name,
                'dropshipper_id' => auth()->id(),
            ]);

            // Return a response or perform any other actions
            return response()->json($responseData);
        } catch (\Exception $e) {
            // Handle any errors or exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function seedDatabase(Request $request)
    {
        try {
            $client = new Client([
                'base_uri' => 'https://' . $request->username . '.store.ollsell.olltek.com/'
            ]);

            $response = $client->get('company/seed-data');

            // Process the response as needed
            $responseData = json_decode($response->getBody(), true);

            UserDomain::where([
                'username' => $request->username,
            ])->update(['seeded' => true]);

            // Return a response or perform any other actions
            return response()->json($responseData);
        } catch (\Exception $e) {
            // Handle any errors or exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
