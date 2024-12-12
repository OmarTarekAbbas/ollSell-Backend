<?php

namespace Modules\MasterCatalog\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\MasterCatalog\Entities\Product;
use Modules\MasterCatalog\Http\Resources\Favorite\FavoriteResource;
use Modules\MasterCatalog\Repositories\FavoriteRepository;
//todo change
class FavoriteService extends BasicService
{
    /* A protected variable that is used to store the repository object. */
    protected $repo;
    protected $productService;

    /* Not doing anything. It is not being used anywhere in the code. */
    protected $resource;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(FavoriteRepository $repository, ProductService $productService)
    {
        $this->repo = $repository;
        $this->productService = $productService;
    }

    /**
     * It returns a collection of ProductListResource
     *
     * param  request This is the request object that is passed to the controller.
     * param pagination true/false
     * param perPage The number of items to show per page.
     *
     * return A collection of ProductListResource
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        $recursiveRel = [
            'dropshipper' => [
                'type' => 'whereHas',
                'where' => ['dropshipper_id' => [user()->id]]
            ]
        ];
        return  $this->productService->list($request, $pagination, $perPage, $recursiveRel);
    }

    /**
     * It returns a new ProductListResource object, which is a collection of
     * ProductListResource objects
     *
     * param id The id of the master catalog list you want to retrieve
     *
     * return A new instance of the ProductListResource class.
     */
    public function show($id)
    {
        $data =  $this->repo->findOne($id);
        if ($data) {
            return new FavoriteResource($this->repo->findOne($id));
        }
        return null;
    }

    /**
     * It takes a request, a boolean for pagination, and a number for the number of items per page
     *
     * param Request request The request object
     * param pagination true or false
     * param perPage The number of items to show per page.
     *
     * return A collection of objects.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10, $get = '')
    {
        return $this->repo->findBy($request, $pagination,  $perPage, $get);
    }

    /**
     * It takes a request, passes it to the repo, and returns true if the repo returns a value
     *
     * param Request request The request object
     *
     * return A boolean value.
     */
    public function remove(Request $request)
    {
        $products = $request->products;

        foreach($products as $record) {
            $product = Product::find($record);
            $profit = $product->queryProfitProduct();

            if($profit) {
                $profit->delete();
            }

            $product->update([
                'isManual' => false,
                'profit' => user()->profit
            ]);
        }

        $data = $this->findBy(new Request(['dropshipper_id' => user()->id, 'product_id' => $products]), get: 'delete');
        if ($data) {
            return true;
        }
        return false;
    }

    /**
     * If the user has favorited the post, return true, otherwise return false.
     *
     * param request
     *
     * return A boolean value.
     */
    public function existsInFavorites($request)
    {
        $products = $request->products;
        $existsInFavorites = $this->findBy(new Request(['dropshipper_id' => user()->id, 'product_id' => $products]));
        if (count($existsInFavorites)) {
            return true;
        }
        return false;
    }
}
