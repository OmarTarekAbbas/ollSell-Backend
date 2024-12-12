<?php

namespace Modules\MasterCatalog\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\MasterCatalog\Http\Requests\Favorite\CreateRequest;
use Modules\MasterCatalog\Service\FavoriteService;
use Maatwebsite\Excel\Facades\Excel;
use Modules\MasterCatalog\Exports\Favorite\FavoriteExport;
use Modules\MasterCatalog\Service\ProductService;

/**
 * @group Favorite management
 *
 * APIs for managing favorite list
 */
class FavoriteController extends BasicController
{
    private $service;
    private $productService;

    /**
     * This is a constructor function that requires authentication for a dropshipper and initializes a
     * FavoriteService object.
     *
     * param FavoriteService Service The parameter `` is an instance of the `FavoriteService`
     * class that is being injected into the constructor of the current class. This is a form of
     * dependency injection, where the `FavoriteService` class is being used by the current class to
     * perform certain operations. The `FavoriteService` class
     */
    public function __construct(FavoriteService $Service, ProductService $productService)
    {//todo change
        $this->middleware('auth:dropshipper');
        $this->service = $Service;
        $this->productService = $productService;
    }

    /**
     * It takes a request, merges the request with the user's target market, and then returns the
     * response from the service
     *
     * param Request request The request object
     *
     * return The list of all the users in the database.
     */
    public function list(Request $request)
    {
        // if ($request->search || Favorite::where('dropshipper_id', user()->id)->count() == 0) {
        $recursiveRel = [
            'dropshipper' => [
                'type' => 'whereHas',
                'where' => ['dropshipper_id' => [user()->id]]
            ]
        ];

        $request->merge([ 'isApproved' =>  1]);
        $request->merge(['orderBy' => ['column' => 'quantity', 'order' => 'desc']]);

        return $this->apiResponse($this->productService->list($request, $this->pagination(), $this->perPage(), available: true, recursiveRel: $recursiveRel));
        // }
        // return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage()));
    }

    public function index(Request $request)
    {
        $request->merge(['orderBy' => ['column' => 'quantity', 'order' => 'desc']]);

        return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage()));
    }

    /**
     * It takes the id of a product and returns the product with the target market of the user
     *
     * param id The id of the record you want to show
     *
     * return The show method is returning the result of the service show method.
     */
    public function show($id)
    {
        if ($this->service->show($id)) {
            return $this->apiResponse($this->service->show($id));
        }
        return $this->notFoundResponse(trans('orders.notFound'));
    }

    /**
     * It checks if the product exists in the favorites table, if it does, it returns an error message,
     * if it doesn't, it adds the product to the favorites table
     *
     * param CreateRequest request
     */
    public function add(CreateRequest $request)
    {
        // if (user()->profit === 0.0) return $this->unKnowError('please sets the Profit Margin for the first time');
        if ($this->service->existsInFavorites($request)) return $this->unKnowError( trans('products.You have selected products before'));

        $favorites = $this->service->store($request);
        if ($favorites) {
            return $this->createResponse($favorites, trans('products.Successfully add To My Product'));
        }
        return $this->unKnowError();
    }

    /**
     * It updates or Create the profit of a cost product
     *
     * param Request request The request object.
     *
     * return The response is being returned as a JSON object.
     */
    public function remove(CreateRequest $request)
    {
        $profit = $this->service->remove($request);

        if ($profit) {
            return $this->createResponse($profit, trans('products.Successfully remove From My Product'));
        }
        return  $this->unKnowError(trans('orders.notFound'));
    }

    /**
     * The function is called export, it takes a request as a parameter, and it returns an Excel file
     * called favoriteProducts.xlsx
     *
     * param Request request The request object.
     *
     * return The export function is returning an Excel file.
     */
    public function export()
    {
        return Excel::download(new FavoriteExport,  'favoriteProducts.xlsx');
    }
}
