<?php

namespace Modules\Order\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Order\Http\Resources\Cart\CartResource;
use Modules\Order\Service\CartService;
use Modules\Order\Http\Requests\Cart\EditRequest;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Order\Http\Requests\Cart\CreateRequest;

class CartController extends BasicController
{
    protected $service;

    /**
     * The function is a constructor that initializes the CountryService, CityService, and OrderService
     * dependencies, and sets the middleware for authentication.
     *
     * responsible for handling operations related to countries.
     * handling operations related to cities.
     * param OrderService service The `` parameter is an instance of the `OrderService` class.
     * It is used to perform operations related to orders, such as creating, updating, and retrieving
     * orders.
     */
    public function __construct(CartService $service)
    {
        $this->middleware('auth:dropshipper');
        $this->service = $service;
    }

    /**
     * It takes a request, merges the request with the user's target market, and then returns the
     * response from the service
     *
     * param Request request The request object
     *
     * return The list of all the users in the database.
     */
    public function index(Request $request)
    {
        return $this->apiResponse($this->service->list($request->merge(['dropshipper_id'=>user()->id])));
    }

    /**
     * It checks if the product exists in the favorites table, if it does, it returns an error message,
     * if it doesn't, it adds the product to the favorites table
     *
     * param CreateRequest request
     */
    public function store(CreateRequest $request)
    {
        try {
            $cart = $this->service->store($request);

            if ($cart) {
                return $this->createResponse(new CartResource($cart));
            }

            return $this->unKnowError();
        } catch (\Exception $Exception) {
            return $this->unKnowError($Exception->getMessage());
        }
    }

    /**
     * It updates the order and returns the updated order if it's updated successfully, otherwise it
     * returns an unknown error.
     *
     * param Request request The request object.
     * param id The id of the order you want to update.
     */
    public function update(EditRequest $request, $id)
    {
        if (!$this->service->show($id)) {
            return $this->notFoundResponse();
        }
        try {
            $cart = $this->service->update($request, $id);
            if ($cart) {
                return $this->updateResponse(new CartResource($cart));
            }

            return $this->unKnowError();
        } catch (\Exception $Exception) {
            return $this->unKnowError($Exception->getMessage());
        }
    }

    public function deleteProduct($id)
    {
        $data = $this->service->findBy(new Request(['dropshipper_id'=>user()->id,'product_id'=>$id]),get:'first');
        if (!$data) {
            return $this->notFoundResponse();
        }
        $idCart = $data->id;
        try {
            return response()->json(['message' => $this->service->destroy(request(), $idCart)]);
        } catch (\Exception $Exception) {
            return $this->unKnowError($Exception->getMessage());
        }
    }

    public function list(Request $request)
    {
        return $this->apiResponse($this->service->list($request));
    }
}
