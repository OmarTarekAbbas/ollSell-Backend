<?php

namespace Modules\MasterCatalog\Service;

use Modules\MasterCatalog\Service\ProductService;
use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\MasterCatalog\Http\Resources\Bundle\BundleResource;
use Modules\MasterCatalog\Repositories\BundleRepository;
use Modules\MasterCatalog\Http\Resources\Bundle\BundleListResource;
use Modules\MasterCatalog\Entities\Bundle;
use Modules\MasterCatalog\Entities\BundleProduct;
use Modules\MasterCatalog\Entities\Product;
use Modules\MasterCatalog\Repositories\BundleProductRepository;

//todo change
class BundleService extends BasicService
{
    protected $repo, $productService;
    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(BundleRepository $repository, ProductService $productService)
    {
        $this->repo = $repository;
        $this->productService = $productService;
    }

    /**
     * This is a PHP function that retrieves data from a repository based on search and status filters,
     * with pagination and recursive relationships.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * contains the HTTP request information such as query parameters, form data, and headers.
     *
     * return the result of a query that retrieves data from a repository based on the parameters
     * passed in the `` object. The query includes additional conditions based on the values of
     * `->search` and `->status`, and also includes pagination and sorting options. The
     * result is returned as an array of data.
     */
    public function index(Request $request, $pagination = false, $perPage = 10)
    {
        $moreConditionForFirstLevel = [];
        $recursiveRel = [];
        if (isset($request->search) && $request->search != null) {
            $moreConditionForFirstLevel += [
                'whereCustom' => [
                    'orWhere' => [
                        ['id' => ['LIKE', '%' . $request->search . '%']]
                    ],
                    'orWhereHas' => [
                        ['translation' =>
                        [
                            'type' => 'orWhereHas',
                            'where' => ['key' => 'name', 'value' => ['LIKE', '%' . $request->search . '%']],
                        ]]
                    ]
                ],
            ];
        }

        return $this->repo->findBy(
            request: $request,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            pagination: $pagination,
            perPage: $perPage
        );
    }


    /**
     * It takes a request, passes it to the repo, and returns the result of the repo's save method.
     *
     * param Request request The request object
     *
     * return The data is being returned.
     */
    public function store(Request $request)
    {
        $data = $this->repo->save($request);
        if ($data) {
            return new BundleResource($data);
        }
        return false;
    }



    public function show($id)
    {
        $data = $this->repo->findOne($id);
        if ($data) {
            return new BundleResource($data);
        }
        return false;
    }



    /**
     * This function returns a list of active categories using a category service.
     *
     * return The function `categoryList()` is returning the list of categories with active status.
     */
    public function list()
    {
        $recursiveRel = [
            'bundle_dropshippers' => [
                'type' => 'whereCustom',
                'value' => [
                    ['bundle_dropshippers' => [
                        'type' => 'whereDoesntHave',
                        'where' => ['dropshipper_id' => ['!=', user()->id]]
                    ]],
                    ['bundle_dropshippers' => [
                        'type' => 'orWhereHas',
                        'where' => ['dropshipper_id' => [user()->id]]
                    ]]
                ]
            ]
        ];
        return BundleListResource::collection($this->findBy(new Request(['status'=>1]),recursiveRel:$recursiveRel));
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
    public function findBy(Request $request, $orderBy = [], $pagination = false, $perPage = 10, $get = '', $moreConditionForFirstLevel = [], $limit = null,$recursiveRel=[])
    {
        return $this->repo->findBy($request, $orderBy, $moreConditionForFirstLevel, $limit, $pagination,  $perPage, $get,$recursiveRel);
    }

    public function getAvailableProducts()
    {
        // Get all products
        $allProducts = Product::all();

        // Get IDs of products that are part of any existing bundles
        $bundledProductIds = BundleProduct::pluck('product_id')->toArray();

        // Filter out products that are already in bundles
        $availableProducts = $allProducts->whereNotIn('id', $bundledProductIds);

        return $availableProducts;
    }

    /**
     * Check if the SKU already exists in the database.
     *
     * param Request request The request object containing the SKU.
     *
     * return bool True if SKU exists, false otherwise.
     */
    public function checkSkuExists(Request $request)
    {
        // Assuming SKU is passed in the request as 'sku'
        return Bundle::where('sku', $request->sku)->exists();
    }

    public function showBundle($id)
    {
        return $this->repo->findOne($id);
    }
}
