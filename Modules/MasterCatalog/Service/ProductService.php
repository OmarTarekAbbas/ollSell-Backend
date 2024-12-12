<?php

namespace Modules\MasterCatalog\Service;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\MasterCatalog\Http\Resources\Product\ProductResource;
use Modules\CoreData\Service\CategoryService;
use Modules\CoreData\Service\TargetMarketService;
use Modules\MasterCatalog\Actions\Product\ButtonScanWMSOrderAction;
use Modules\MasterCatalog\Entities\AttributeOption;
use Modules\MasterCatalog\Entities\AttributeProduct;
use Modules\MasterCatalog\Entities\CategoryProduct;
use Modules\MasterCatalog\Entities\Product;
use Modules\MasterCatalog\Http\Resources\Product\ProductListResource;
use Modules\MasterCatalog\Http\Resources\Product\RelatedProductListResource;
use Modules\MasterCatalog\Repositories\ProductRepository;
use Modules\Order\Enums\OrderEnum;
use Modules\Supplier\Entities\Warehouse;
use Modules\Supplier\Service\WarehouseService;
use Modules\Wms\Actions\Inventory\ScanEvent;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Modules\MasterCatalog\Entities\ProductVariant;
use Modules\MasterCatalog\Entities\Bundle;

//todo change
class ProductService extends BasicService
{
    protected $repo;
    protected $categoryService;
    protected $targetMarketService;
    protected $attributeService;
    protected $productVariantService;
    protected $eventService;
    protected $warehouseService;

    /**
     * This is a constructor function that initializes the repository, category service, and target
     * market service.
     *
     * param ProductRepository repository The  parameter is an instance of the
     * ProductRepository class, which is responsible for handling database operations related to
     * products. It likely contains methods for retrieving, creating, updating, and deleting product
     * records from the database.
     * param CategoryService categoryService It is an instance of the CategoryService class, which is
     * likely responsible for handling operations related to product categories such as creating,
     * updating, and deleting categories, as well as retrieving category information from the database.
     * param TargetMarketService targetMarketService The  parameter is an instance
     * of the TargetMarketService class, which is a service that provides functionality related to
     * target markets. It is likely used within the constructor to perform operations related to target
     * markets, such as retrieving or updating data.
     * param AttributeService attributeService The  parameter is an instance
     * of the AttributeService class, which is a service that provides functionality related to
     * Product attributes. It is likely used within the constructor to perform operations related to attributes
     * such as retrieving or updating data.
     * param  productVariantService The  parameter is an instance
     * of the ProductVariantService class, which is a service that provides functionality related to
     * Product attributes. It is likely used within the constructor to perform operations related to attributes
     * such as retrieving or updating data.
     * param  EventService The  parameter is an instance
     * of the ProductVariantService class, which is a service that provides functionality related to
     * Product attributes. It is likely used within the constructor to perform operations related to attributes
     * such as retrieving or updating data.
     */
    public function __construct(
        ProductRepository $repository,
        CategoryService $categoryService,
        TargetMarketService $targetMarketService,
        AttributeService $attributeService,
        ProductVariantService $productVariantService,
        EventService $eventService,
        WarehouseService $warehouseService
    )
    {
        $this->repo = $repository;
        $this->categoryService = $categoryService;
        $this->targetMarketService = $targetMarketService;
        $this->attributeService = $attributeService;
        $this->productVariantService = $productVariantService;
        $this->eventService = $eventService;
        $this->warehouseService = $warehouseService;
    }

    /**
     * This function finds records based on specified conditions and returns them with optional
     * pagination and ordering.
     *
     * param Request request  is an instance of the Request class in Laravel. It contains the
     * HTTP request information such as the request method, headers, and input data. In this function,
     * it is used to retrieve any search or filter criteria that may be passed in the request.
     * param moreConditionForFirstLevel An array of additional conditions to be applied to the first
     * level of the query. These conditions will be added to the WHERE clause of the SQL query.
     * param pagination A boolean value that determines whether or not to paginate the results. If set
     * to true, the results will be paginated based on the  parameter. If set to false, all
     * results will be returned.
     * param perPage The number of records to be displayed per page in case of pagination.
     * param orderBy An array that specifies the order in which the results should be sorted. The keys
     * of the array represent the columns to sort by, and the values represent the direction of the
     * sort (either "asc" for ascending or "desc" for descending). For example, ["name" => "asc", "
     * param recursiveRel The  parameter is an array that specifies the related models
     * that should be eager loaded when retrieving the data. This is useful for reducing the number of
     * database queries needed to retrieve related data. For example, if a model has a relationship
     * with another model, specifying that relationship in the
     * param get The "get" parameter is used to specify which columns to retrieve from the database.
     * It can be a string or an array of column names. If not specified, it will retrieve all columns.
     *
     * return the result of calling the `findBy` method on the `` object with the arguments
     * passed to the function.
     */
    public function findBy(
        Request $request,
        $moreConditionForFirstLevel = [],
        $pagination = false,
        $perPage = 10,
        $orderBy = [],
        $recursiveRel = [],
        $get = '',
        $withRelations = []
    )
    {
        return $this->repo->findBy(
            $request,
            $pagination,
            $perPage,
            $orderBy,
            $moreConditionForFirstLevel,
            $recursiveRel,
            $get,
            withRelations: $withRelations
        );
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
        if(isset($request->search) && $request->search != null)
        {
            $moreConditionForFirstLevel += [
                'whereCustom' => [
                    'orWhere' => [
                        ['id' => ['LIKE', '%' . $request->search . '%']],
                        ['sku' => ['LIKE', '%' . $request->search . '%']],
                    ],
                    'orWhereHas' => [
                        ['translation' => [
                            'type' => 'orWhereHas',
                            'where' => ['key' => 'name', 'value' => ['LIKE', '%' . $request->search . '%']],
                        ]]
                    ]
                ],
            ];
        }
        if(isset($request->quantity_status) && $request->quantity_status != "null")
        {
            if($request->quantity_status == 1)
            {
                $moreConditionForFirstLevel += ['where' => ['quantity' => ['>', 0]]];
            }
            if($request->quantity_status == 0)
            {
                $recursiveRel += [
                    'orderItems' => [
                        'type' => 'whereHas',
                        'where' => ['status_id' => [OrderEnum::PENDING_INVENTORY_STATUS]],
                    ],
                ];
            }
            if($request->quantity_status == 2)
            {
                $moreConditionForFirstLevel += ['where' => ['quantity' => ['=', 0]]];
            }
        }
        if($request->fromDate && $request->toDate)
        {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [Carbon::parse($request->fromDate)
                ->startOfDay(), Carbon::parse($request->toDate)->endOfDay()]]];
        }
        if(isset($request->is_report) && $request->is_report)
        {
            if($request->fromDate && $request->toDate)
            {
                $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [Carbon::parse($request->fromDate)
                    ->startOfDay(), Carbon::parse($request->toDate)->endOfDay()]]];
            }elseif($request->fromDate)
            {
                $moreConditionForFirstLevel += ['where' => ['created_at' => ['>=', Carbon::parse($request->fromDate)
                    ->startOfDay()]]];
            }elseif($request->toDate)
            {
                $moreConditionForFirstLevel += ['where' => ['created_at' => ['<=', Carbon::parse($request->toDate)
                    ->endOfDay()]]];
            }
            if(isset($request->product_id))
            {
                $moreConditionForFirstLevel = ['where' => ['id' => $request->product_id]];
            }
            if(isset($request->dropshipper_id) && !empty($request->dropshipper_id))
            {
                $recursiveRel += [
                    'orderItems' => [
                        'type' => 'whereHas',
                        'recursive' => [
                            'order' => [
                                'type' => 'whereHas',
                                'whereIn' => ['dropshipper_id' => $request->dropshipper_id],
                            ],
                        ],
                    ],
                ];
            }
        }
        return $this->repo->findBy(
            request: $request,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            pagination: $pagination,
            perPage: $perPage,
            recursiveRel: $recursiveRel,
            orderBy: ['column' => 'id', 'order' => 'desc']
        );
    }

    public function listHome(Request $request, $pagination = false, $perPage = 10)
    {
        $orderBy = [];
        $moreConditionForFirstLevel = [];
        $request->merge(['status' => 1]);
        if($request->is_recentlyAdd)
        {
            $orderBy = ['column' => 'id', 'order' => 'desc'];
            $moreConditionForFirstLevel = ['where' => ['created_at' => ['>', Carbon::now()->subDays(30)
                ->format('Y-m-d 00:00:00')]]];
        }
        if($request->is_mostOrder)
        {
            $moreConditionForFirstLevel = ['where' => ['saleCountProduct' => ['>', '0']]];
        }
        $recentlyAdded = ProductListResource::collection($this->recentlyAdded($request, $moreConditionForFirstLevel));
        $mostOrdered = ProductListResource::collection($this->mostOrdered($request, $orderBy));
        $specialOffers = ProductListResource::collection($this->specialOffers(
            $request,
            $moreConditionForFirstLevel,
            $orderBy
        ));
        return [
            'specialOffers' => $specialOffers,
            'recentlyAdded' => $recentlyAdded,
            'mostOrdered' => $mostOrdered,
        ];
    }

    public function mostOrdered(Request $request, $orderBy)
    {
        $moreConditionForFirstLevel = ['where' => ['saleCountProduct' => ['>', '0']]];
        return $this->repo->findBy(
            $request,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            orderBy: $orderBy,
            perPage: 24,
            pagination: true
        );
    }

    public function specialOffers(Request $request, $moreConditionForFirstLevel, $orderBy)
    {
        $newRequest = $request;
        $newRequest->merge(['is_discount' => 1]);
        return $this->repo->findBy(
            $newRequest,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            orderBy: $orderBy,
            perPage: 24,
            pagination: true
        );
    }

    public function categoryProductList(Request $request, $pagination = false, $perPage = 10)
    {
        return $this->categoryService->listWithProduct($request, $pagination, $perPage);
    }

    /**
     * The function `listProductsSupplier` retrieves a list of products based on the search and status
     * criteria provided in the request.
     *
     * param Request request The `` parameter is an instance of the `Illuminate\Http\Request`
     * class. It represents the current HTTP request made to the server and contains information such
     * as the request method, URL, headers, and any data sent with the request.
     *
     * return the result of the `findBy` method of the `` object. The `findBy` method is called
     * with several parameters including the `` object, ``,
     * `pagination`, `perPage`, `recursiveRel`, and `orderBy`. The result of the `findBy` method is
     * being returned.
     */
    public function listProductsSupplier(Request $request)
    {
        $tableLength = session('table_length') ?? config('app.pagination_pages');
        $moreConditionForFirstLevel = [];
        $recursiveRel = [];
        if(isset($request->search) && $request->search != null)
        {
            $moreConditionForFirstLevel += ['orWhere' => ['id' => [$request->search], 'sku' => ['LIKE', '%' . $request->search . '%']]];
            $recursiveRel = ['translation' => [
                'type' => 'whereHas',
                'where' => ['value' => ['LIKE', '%' . $request->search . '%']],
            ]];
        }
        if(isset($request->status) && $request->status != 'null')
        {
            $moreConditionForFirstLevel += ['where' => ['status' => [$request->status]]];
        }
        $request->merge(['isApproved' => 0]);
        $moreConditionForFirstLevel += ['where' => ['isApproved' => 0]];
        return $this->repo->findBy(
            $request,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            pagination: true,
            perPage: $tableLength,
            recursiveRel: $recursiveRel,
            orderBy: ['column' => 'updated_at', 'order' => 'desc']
        );
    }

    /**
     * This function saves data to the database.
     *
     * param Request request an instance of the Request class, which contains the data submitted in
     * the HTTP request
     */
    public function store(Request $request)
    {
        $request['isApproved'] = 1;
        $request->merge(['target_market_id' => ['3']]);
        if($request->has('variants') && $request->has('has_variants'))
        {
            $request->merge(['variants_data' => json_encode($request->variants)]);
        }else
        {
            $request->merge(['variants_data' => json_encode([])]);
        }
        $request->merge(['quantity' => 0]);
        $request->merge(['name' => ['en' => $request->name, 'ar' => $request->name], 'description' => ['en' => $request->description, 'ar' => $request->description]]);
        $product = $this->repo->save($request);
        $request['product_id'] = $product->id;
        $request['product_price'] = $product->cost_price;
        if($request->has('has_variants'))
        {
            $this->productVariantService->store($request->all());
        }
        if($request->has('category_Ids'))
        {
            $categoryIds = $request->category_Ids; // An array of category IDs
            $product->categories()->attach($categoryIds);
        }
        if($product->is_wms)
        {
            $this->scanQuantityWms($product->id);
        }
        return $product;
    }

    /**
     * The function updates a product, sets it as approved, and saves it to the database, along with
     * its variants and variant values if provided.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains all the data sent with the request, such as form inputs,
     * query parameters, and headers.
     * param id The  parameter is the identifier of the product that needs to be updated. It is
     * used to retrieve the specific product from the database and update its details.
     *
     * return the updated product.
     */
    public function update(Request $request, $id)
    {
        $request['isApproved'] = 1;
        if($request->has('variants') && $request->has('has_variants'))
        {
            $request->merge(['variants_data' => json_encode($request->variants)]);
        }else
        {
            $request->merge(['variants_data' => json_encode([])]);
        }
        if($request->custam_commission == null)
        {
            $request->merge(['custam_commission' => 0]);
        }
        $request->merge(['name' => ['en' => $request->name, 'ar' => $request->name], 'description' => ['en' => $request->description, 'ar' => $request->description]]);
        $product = $this->repo->save($request, $id);
        $request['product_id'] = $product->id;
        $request['product_price'] = $product->cost_price;
        $this->removeAllVariants($product);
        if($request->has('variants') && $request->has('has_variants'))
        {
            $this->productVariantService->store($request->all());
        }
        if($request->has('category_Ids'))
        {
            $catProducts = CategoryProduct::where('product_id', $product->id)->get();
            // Process the results
            foreach($catProducts as $catProduct)
            {
                // Do something with each row
                $catProduct->delete();
            }
            $categoryIds = $request->category_Ids; // An array of category IDs
            $product->categories()->attach($categoryIds);
        }else
        {
            $catProducts = CategoryProduct::where('product_id', $product->id)->get();
            foreach($catProducts as $catProduct)
            {
                $catProduct->delete();
            }
        }
        return $product;
    }

    private function removeAllVariants($product)
    {
        $product->productVariants()->delete();
        $product->productVariantValues()->delete();
        AttributeProduct::where(['product_id' => $product->id])->delete();
        if(isset($product->attributes))
        {
            foreach($product->attributes as $attr)
            {
                AttributeOption::where('attribute_id', $attr->id)->delete();
            }
        }
    }

    /**
     * This function returns a list of active categories using a category service.
     *
     * return The function `categoryList()` is returning the list of categories with active status.
     */
    public function categoryList()
    {
        return $this->categoryService->list(new Request(['status' => activeType()['as']]));
    }

    /**
     * It returns a list of target markets from the database.
     *
     * return The return value is an array of objects.
     */
    public function targetMarketList()
    {
        return $this->targetMarketService->list(new Request(['status' => activeType()['as']]));
    }

    /**
     * It returns a collection of ProductListResource
     *
     * param Request request This is the request object that is passed to the controller.
     * param pagination true/false
     * param perPage The number of items to show per page.
     *
     * return A collection of ProductListResource
     */
    public function list(
        Request $request,
        $pagination = false,
        $perPage = 10,
        $recursiveRel = [],
        $available = false,
        $withRelations = []
    )
    {
        $moreConditionForFirstLevel = [];
        $recursiveRel += [
            'product_dropshippers' => [
                'type' => 'whereCustom',
                'value' => [
                    ['product_dropshippers' => [
                        'type' => 'whereDoesntHave',
                        'where' => ['dropshipper_id' => ['!=', user()->id]]
                    ]],
                    ['product_dropshippers' => [
                        'type' => 'orWhereHas',
                        'where' => ['dropshipper_id' => [user()->id]]
                    ]]
                ]
            ]
        ];
        if($available)
        {
            $moreConditionForFirstLevel += ['where' => ['quantity' => ['>', '0']]];
        }
        if($request->search)
        {
            $moreConditionForFirstLevel += [
                'whereCustom' => [
                    'orWhere' => [
                        ['id' => ['LIKE', '%' . $request->search . '%']],
                        ['sku' => ['LIKE', '%' . $request->search . '%']],
                    ],
                    'orWhereHas' => [
                        ['translation' => [
                            'type' => 'orWhereHas',
                            'where' => ['key' => 'name', 'value' => ['LIKE', '%' . $request->search . '%']],
                        ]]
                    ]
                ],
            ];
        }
        if($request->has('category_id'))
        {
            // Add category_id filter if present in the request
            $categoryId = $request->category_id;
            $moreConditionForFirstLevel['whereHas'] = [
                'categories' => function($query) use ($categoryId)
                {
                    $query->where('categories.id', $categoryId);
                },
            ];
        }
        if($request->fromDate && $request->toDate)
        {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [Carbon::parse($request->fromDate), Carbon::parse($request->toDate)]]];
        }elseif($request->fromDate)
        {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['>=', Carbon::parse($request->fromDate)]]];
        }elseif($request->toDate)
        {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['<=', Carbon::parse($request->toDate)]]];
        }
        if($request->fromQuantity && $request->toQuantity)
        {
            $moreConditionForFirstLevel += ['whereBetween' => ['quantity' => [$request->fromQuantity, $request->toQuantity]]];
        }elseif($request->fromQuantity)
        {
            $moreConditionForFirstLevel += ['where' => ['quantity' => ['>=', $request->fromQuantity]]];
        }elseif($request->toQuantity)
        {
            $moreConditionForFirstLevel += ['where' => ['quantity' => ['<=', $request->toQuantity]]];
        }
        if($request->fromDate && $request->toDate)
        {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [Carbon::parse($request->fromDate), Carbon::parse($request->toDate)]]];
        }elseif($request->fromDate)
        {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['>=', Carbon::parse($request->fromDate)]]];
        }elseif($request->toDate)
        {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['<=', Carbon::parse($request->toDate)]]];
        }
        if($request->fromQuantity && $request->toQuantity)
        {
            $moreConditionForFirstLevel += ['whereBetween' => ['quantity' => [$request->fromQuantity, $request->toQuantity]]];
        }elseif($request->fromQuantity)
        {
            $moreConditionForFirstLevel += ['where' => ['quantity' => ['>=', $request->fromQuantity]]];
        }elseif($request->toQuantity)
        {
            $moreConditionForFirstLevel += ['where' => ['quantity' => ['<=', $request->toQuantity]]];
        }
        if($request->event_id)
        {
            $recursiveRel += ['events' => [
                'type' => 'whereHas',
                'where' => ['event_id' => $request->event_id],
            ]];
        }
        return ProductListResource::collection($this->repo->list(
            $request,
            $pagination,
            $perPage,
            $moreConditionForFirstLevel,
            $recursiveRel,
            $withRelations
        ));
    }

    public function chunkProducts(Request $request)
    {
        return $this->repo->chunkProducts($request);
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
        return $this->repo->findOne($id);
    }

    public function showProduct($id)
    {
        return $this->repo->findOne($id);
    }

    /**
     * It returns an array of three arrays, each of which contains a list of products
     *
     * param Request request The request object
     * param pagination if you want to paginate the result or not
     * param perPage The number of items to be shown per page.
     */
    public function recentlList(Request $request, $pagination = false, $perPage = 10)
    {
        $request->merge(['status' => activeType()['as'], 'isApproved' => 1]);
        $moreConditionForFirstLevel = ['where' => ['created_at' => ['>', Carbon::now()->subDays(7)
            ->format('Y-m-d 00:00:00')]]];
        $recursiveRel = [
            'product_dropshippers' => [
                'type' => 'whereDoesntHave',
                'where' => ['dropshipper_id' => ['!=', user()->id]],
            ]
        ];
        return ProductListResource::collection($this->recentlyAdded(
            $request,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            pagination: $pagination,
            perPage: $perPage,
            recursiveRel: $recursiveRel
        ));
    }

    /**
     * This function reports products based on certain conditions and returns the results with
     * pagination.
     *
     * param Request request This is an instance of the Request class, which is used to retrieve data
     * from the HTTP request (e.g. query parameters, form data, etc.).
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the perPage parameter. If set to
     * false, all results will be returned without pagination.
     * param perPage The number of records to be displayed per page in the pagination. In this case,
     * it is set to 10.
     *
     * return The function `reportProduct` is returning the result of a database query using the
     * `findBy` method of the repository object. The query is filtered by the `` parameter and
     * additional conditions specified in the `` array. The results are
     * ordered by the `id` column in descending order, and paginated with 4 items per page if
     * `` is set to
     */
    public function reportProduct(Request $request, $pagination = false, $perPage = 10)
    {
        $orderBy = ['column' => 'id', 'order' => 'desc'];
        $moreConditionForFirstLevel = ['where' => ['saleCountProduct' => ['>', '0']]];
        return $this->repo->findBy(
            $request,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            orderBy: $orderBy,
            perPage: 4,
            pagination: true
        );
    }

    /**
     * It returns the result of the findBy function in the repo class
     *
     * param Request request The request object
     * param moreConditionForFirstLevel This is the condition that will be added to the first level of
     * the query.
     *
     * return An array of objects.
     */
    public function recentlyAdded(
        Request $request,
        $moreConditionForFirstLevel = [],
        $pagination = false,
        $perPage = 10,
        $recursiveRel = []
    )
    {
        return $this->repo->findBy(
            $request,
            orderBy: ['column' => 'quantity', 'order' => 'desc'],
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            perPage: $perPage,
            pagination: $pagination,
            recursiveRel: $recursiveRel
        );
    }

    /**
     * This PHP function downloads a file if it exists and deletes other files.
     *
     * return BinaryFileResponse|string Either a `BinaryFileResponse` object (if the file exists and
     * is successfully downloaded) or a string "not found" (if the file does not exist).
     */
    public function download(): BinaryFileResponse|string
    {
        //todo remove duplication
        if(ob_get_contents())
        {
            ob_end_clean();
        }
        $path = public_path('/missings/products_failed_rows.xlsx');
        if(file_exists($path))
        {
            return response()->download($path);
        }
        return 'not found';
    }

    /**
     * It returns a list of target markets from the database.
     *
     * return The return value is an array of objects.
     */
    public function getAttributesList()
    {
        return $this->attributeService->findBy(new Request);
    }

    /**
     * It returns a list of eventd from the database.
     *
     * return The return value is an array of objects.
     */
    public function getEventsList()
    {
        return $this->eventService->findBy(new Request);
    }

    /**
     * It returns a list of eventd from the database.
     *
     * return The return value is an array of objects.
     */
    public function getWarehouses()
    {
        return $this->warehouseService->findBy(new Request(['is_internal' => 0]));
    }

    /**
     * The function returns a warehouse object that has the "is_internal" property set to 1.
     *
     * return the result of the findBy method called on the warehouseService object. The findBy method
     * is being passed a Request object with the parameter 'is_internal' set to 1.
     */
    public function getWarehouseIsInternal()
    {
        return Warehouse::where('is_internal', 1)->first();
    }

    /**
     * The function deletes a product image using the provided request.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve data from the HTTP request. It contains information such as the request method,
     * headers, and any data sent in the request body. In this case, it is used to pass the necessary
     * data to the deleteImage method
     *
     * return The deleteImage method from the repo object is being returned.
     */
    public function deleteProductImage(Request $request)
    {
        return $this->repo->deleteImage($request);
    }

    /**
     * The function updates the quantity of a product by subtracting 1.
     *
     * param productId The productId parameter is the unique identifier of the product that needs to
     * be updated.
     */
    public function updateQuantitySubtracting($productId, $quantity)
    {
        $product = Product::find($productId);
        $product->quantity = $product->quantity - $quantity;
        $product->save();
    }

    /**
     * The function updates the quantity of a product variant by subtracting a specified quantity.
     *
     * param variantId The variantId parameter is the unique identifier of the product variant that
     * you want to update the quantity for.
     * param quantity The quantity parameter represents the number of items to be deducted from the
     * current quantity of a product variant.
     */
    public function updateQuantityVariant($variantId, $productId, $quantity)
    {
        $productVariantValues = ProductVariant::find($variantId);
        $productVariantValues->quantity = $productVariantValues->quantity - $quantity;
        if($productVariantValues->save())
        {
            $product = Product::find($productId);
            $product->quantity = $product->quantity - $quantity;
            $productJson = json_decode($product->variants_data, true);
            $productJson[0]['quantity'] = $productVariantValues->quantity;
            $product->variants_data = json_encode($productJson);
            $product->save();
        }
    }

    /**
     * The function updates the quantity of a product by incrementing it by 1.
     *
     * param productId The productId parameter is the unique identifier of the product that needs to
     * be updated.
     */
    public function updateQuantityIncrementing($productId, $quantity)
    {
        $product = Product::find($productId);
        $product->quantity = $product->quantity + $quantity;
        $product->saleCountProduct = $product->saleCountProduct - $quantity;
        $product->save();
    }

    /**
     * The function updates the quantity of a product variant and its corresponding product by
     * incrementing it with the given quantity.
     *
     * param productId The ID of the product that needs to be updated.
     *
     * param variantId The variantId parameter is the unique identifier of the product variant that
     * you want to update the quantity for.
     *
     * param quantity The quantity parameter represents the amount by which the quantity of a product
     * variant should be incremented.
     */
    public function updateQuantityVariantIncrementing($productId, $variantId, $quantity)
    {
        $productVariantValues = ProductVariant::find($variantId);
        $productVariantValues->quantity = $productVariantValues->quantity + $quantity;
        if($productVariantValues->save())
        {
            $product = Product::find($productId);
            $product->quantity = $product->quantity + $quantity;
            $productJson = json_decode($product->variants_data, true);
            $productJson[0]['quantity'] = $productVariantValues->quantity;
            $product->variants_data = json_encode($productJson);
            $product->save();
        }
    }

    /**
     * The function updates the sale count of a product in a database by incrementing it by 1.
     *
     * param productId The productId parameter is the unique identifier of the product that needs to
     * be updated.
     */
    public function updateSaleCountProduct($productId, $quantity)
    {
        $product = Product::find($productId);
        $product->saleCountProduct = $product->saleCountProduct + $quantity;
        $product->save();
    }

    /**
     * The function "approvedProductsSupplier" updates the "isApproved" field of a product to 1,
     * indicating that it has been approved.
     *
     * param request The  parameter is typically an instance of the Illuminate\Http\Request
     * class, which represents the current HTTP request. It contains information about the request such
     * as the request method, headers, and input data.
     * param id The id parameter is the unique identifier of the product that needs to be approved.
     */
    public function approvedProductsSupplier($request, $id)
    {
        $product = Product::find($id);
        $product->isApproved = 1;
        return $product->save();
    }

    /**
     * The function "showSku" takes a request and a name as parameters, merges the name into the
     * request, and then calls the "list" method on the repository with the modified request.
     *
     * param request The  parameter is an instance of the Request class, which contains all
     * the information about the current HTTP request.
     * param name The name parameter is a string that represents the name of the SKU (Stock Keeping
     * Unit) that you want to display.
     */
    public function showSku($sku)
    {
        return Product::where('sku', $sku)->first();
    }

    /**
     * The function `indexExportByAdmin` retrieves data based on search and status criteria, and
     * returns the results.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request made to the server. It contains information about the request, such
     * as the request method, URL, headers, and any data sent with the request.
     *
     * return the result of the `findBy` method of the `` object. The `findBy` method is called
     * with several parameters including the `` object, ``,
     * `pagination`, `perPage`, `recursiveRel`, and `orderBy`. The result of the `findBy` method is
     * being returned.
     */
    public function indexExportByAdmin(Request $request)
    {
        $moreConditionForFirstLevel = [];
        $recursiveRel = [];
        if(isset($request->search) && $request->search != null)
        {
            $moreConditionForFirstLevel += ['orWhere' => ['id' => [$request->search], 'sku' => ['LIKE', '%' . $request->search . '%']]];
            $recursiveRel = ['translation' => [
                'type' => 'whereHas',
                'where' => ['value' => ['LIKE', '%' . $request->search . '%']],
            ]];
        }
        if(isset($request->status) && $request->status != 'null')
        {
            $moreConditionForFirstLevel += ['where' => ['status' => [$request->status]]];
        }
        if(isset($request->quantity_status) && $request->quantity_status != "null")
        {
            if($request->quantity_status == 1)
            {
                $moreConditionForFirstLevel += ['where' => ['quantity' => ['>', 0]]];
            }
            if($request->quantity_status == 0)
            {
                $recursiveRel += [
                    'orderItems' => [
                        'type' => 'whereHas',
                        'where' => ['status_id' => [OrderEnum::PENDING_INVENTORY_STATUS]],
                    ],
                ];
            }
            if($request->quantity_status == 2)
            {
                $moreConditionForFirstLevel += ['where' => ['quantity' => ['=', 0]]];
            }
        }
        if($request->fromDate && $request->toDate)
        {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [Carbon::parse($request->fromDate)
                ->startOfDay(), Carbon::parse($request->toDate)->endOfDay()]]];
        }elseif ($request->fromDate) {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['>=', Carbon::parse($request->fromDate)->startOfDay()]]];
        } elseif ($request->toDate) {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['<=', Carbon::parse($request->toDate)->endOfDay()]]];
        }
        return $this->repo->findBy(
            $request,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            pagination: false,
            perPage: 0,
            recursiveRel: $recursiveRel,
            orderBy: ['column' => 'id', 'order' => 'desc']
        );
    }

    public function exportForSupplier(Request $request)
    {
        $moreConditionForFirstLevel = [];
        $recursiveRel = [];
        if(isset($request->search) && $request->search != null)
        {
            $moreConditionForFirstLevel += ['orWhere' => ['id' => [$request->search], 'sku' => ['LIKE', '%' . $request->search . '%']]];
            $recursiveRel = ['translation' => [
                'type' => 'whereHas',
                'where' => ['value' => ['LIKE', '%' . $request->search . '%']],
            ]];
        }
        if(isset($request->status) && $request->status != 'null')
        {
            $moreConditionForFirstLevel += ['where' => ['status' => [$request->status]]];
        }
        return $this->repo->findBy(
            $request,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            pagination: false,
            perPage: 0,
            recursiveRel: $recursiveRel,
            orderBy: ['column' => 'id', 'order' => 'desc']
        );
    }

    public function search(Request $request)
    {
        $request->merge(['isApproved' => 1,'status' => 1]);
        $moreConditionForFirstLevel = $recursiveRel = [];
        if(isset($request->term) && $request->term != null)
        {
            $moreConditionForFirstLevel += [
                'whereCustom' => [
                    'orWhere' => [
                        ['id' => [ $request->term ]],
                        ['sku' => ['LIKE', '%' . $request->term . '%']],
                    ],
                    'orWhereHas' => [
                        ['translation' => [
                            'type' => 'orWhereHas',
                            'where' => ['key' => 'name', 'value' => ['LIKE', '%' . $request->term . '%']],
                        ]]
                    ]
                ],
            ];
        }

        return $this->repo->findBy(
            $request,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            recursiveRel :$recursiveRel
        );
    }

    public function relatedProduct($id)
    {
        $data = $this->repo->findOne($id);
        return RelatedProductListResource::collection($data->related_products);
    }

    public function scanQuantityWms($id)
    {
        App(ScanEvent::class)->execute($id);
    }

    /**
     * The function `scanProductWms` scans a product in a warehouse management system using a button
     * scan action.
     *
     * @param Request request The `Request ` parameter in the `scanProductWms` function is an
     * instance of the `Illuminate\Http\Request` class in Laravel. It represents an HTTP request that
     * is sent to the server and contains all the data and information related to the request, such as
     * form data, headers,
     *
     * @return The `scanProductWms` function is returning the result of executing the
     * `ButtonScanWMSOrderAction` class with the provided request.
     */
    public function scanProductWms(Request $request)
    {
        return App(ButtonScanWMSOrderAction::class)->execute($request);
    }

    /**
     * This function retrieves the IDs of products that are associated with any bundle.
     *
     * return array An array of product IDs that are part of any bundle.
     */
    public function getBundledProductIds()
    {
        // Assuming you have a Bundle model that has a relationship with Product
        $array = Bundle::with('products')->get()->pluck('products.*.product_id')->flatten()->unique()->toArray();
        return $array;
    }

    public function changeStatus($id, $key)
    {
        $data = $this->repo->updateValue($id, $key);
        $data = $this->repo->findOne($data->id);
        if(!$data->status)
        {
            if($data->bundle->count())
            {
                foreach($data->bundle as $bundle)
                {
                    $bundle->bundle->update(['status' => 0]);
                }
            }
        }
        return $data;
    }
}
