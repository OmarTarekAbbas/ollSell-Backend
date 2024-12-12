<?php

namespace Modules\Supplier\Service;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Wms\Services\WMSService;
use Modules\Basic\Service\BasicService;
use Modules\Supplier\Entities\Warehouse;
use Modules\MasterCatalog\Entities\Product;
use Modules\CoreData\Service\CategoryService;
use Modules\MasterCatalog\Service\EventService;
use Modules\MasterCatalog\Entities\AttributeOption;
use Modules\MasterCatalog\Entities\AttributeProduct;
use Modules\MasterCatalog\Service\AttributeService;
use Modules\MasterCatalog\Entities\CategoryProduct;
use Modules\Supplier\Repositories\ProductRepository;
use Modules\MasterCatalog\Service\ProductVariantService;
use Modules\MasterCatalog\Http\Resources\Product\ProductListResource;
use Modules\CoreData\Actions\Notification\SendNotificationByAdminAction;
use Modules\MasterCatalog\Entities\ProductVariant;
use Modules\MasterCatalog\Actions\Product\matchingProductAction;
//todo change
class ProductService extends BasicService
{
    protected $repo;
    protected CategoryService $categoryService;
    protected AttributeService $attributeService;
    protected ProductVariantService $productVariantService;
    protected EventService $eventService;
    protected WarehouseService $warehouseService;
    protected WMSService $WMSService;

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
        AttributeService $attributeService,
        ProductVariantService $productVariantService,
        EventService $eventService,
        WarehouseService $warehouseService,
        WMSService $WMSService
    )
    {
        $this->repo = $repository;
        $this->categoryService = $categoryService;
        $this->attributeService = $attributeService;
        $this->productVariantService = $productVariantService;
        $this->eventService = $eventService;
        $this->warehouseService = $warehouseService;
        $this->WMSService = $WMSService;
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
    public function findBy(Request $request, $moreConditionForFirstLevel = [], $pagination = false, $perPage = 10,
        $orderBy = [], $recursiveRel = [], $get = '')
    {
        return $this->repo->findBy($request, $pagination, $perPage, $orderBy, $moreConditionForFirstLevel,
            $recursiveRel, $get);
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
    public function index(Request $request,$pagination= false, $perPage =10)
    {
        $moreConditionForFirstLevel = [];
        $recursiveRel = [];
        if(isset($request->search) && $request->search != null)
        {
            $moreConditionForFirstLevel += ['orWhere' => ['id' => [$request->search], 'sku' => ['LIKE', '%' . $request->search . '%']]];
            $recursiveRel = ['translation' =>
                [
                    'type' => 'whereHas',
                    'where' => ['value' => ['LIKE', '%' . $request->search . '%']],
                ]];
        }
        if(isset($request->status) && $request->status != "null")
        {
            $moreConditionForFirstLevel += ['where' => ['status' => [$request->status]]];
        }

        if ($request->fromDate && $request->toDate) {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [Carbon::parse($request->fromDate)->startOfDay(),  Carbon::parse($request->toDate)->endOfDay()]]];
        } elseif ($request->fromDate) {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['>=', Carbon::parse($request->fromDate)->startOfDay()]]];
        } elseif ($request->toDate) {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['<=', Carbon::parse($request->toDate)->endOfDay()]]];
        }
        if(isset($request->product_id))
        {
            $moreConditionForFirstLevel = ['where' => ['id' => $request->product_id]];
        }
        return $this->repo->findBy($request, moreConditionForFirstLevel: $moreConditionForFirstLevel, pagination: $pagination,
            perPage: $perPage, recursiveRel: $recursiveRel, orderBy: ['column' => 'id', 'order' => 'desc']);
    }

    /**
     * This function saves data to the database.
     *
     * param Request request an instance of the Request class, which contains the data submitted in
     * the HTTP request
     */
    public function store(Request $request)
    {
        $request['isApproved'] = 0;
        if($request->has('variants') && $request->has('has_variants'))
        {
            $request->merge(['variants_data' => json_encode($request->variants)]);
        }else
        {
            $request->merge(['variants_data' => json_encode([])]);
        }
        $request->merge(['name' => ['en' => $request->name, 'ar' => $request->name], 'description' => ['en' => $request->description, 'ar' => $request->description]]);
        $product = $this->repo->save($request);
        $request['product_id'] = $product->id;
        $request['product_price'] = $product->cost_price ?? $product->supplier_price_cost;
        if($request->has('has_variants')) $this->productVariantService->store($request->all());
        if($request->has('category_Ids'))
        {
            $categoryIds = $request->category_Ids;
            $product->categories()->attach($categoryIds);
        }
        return $product;
    }

    public function update(Request $request, $id)
    {
        $checkisApproved = (new matchingProductAction(
            $request,
            $id
        ))->execute();
        if($checkisApproved)
        {
            $request['isApproved'] = 0;
        }
        if($request->has('variants') && $request->has('has_variants'))
        {
            $request->merge(['variants_data' => json_encode($request->variants)]);
        }else
        {
            $request->merge(['variants_data' => json_encode([])]);
        }
        $request->merge(['name' => ['en' => $request->name, 'ar' => $request->name], 'description' => ['en' => $request->description, 'ar' => $request->description]]);
        $product = $this->repo->save($request, $id);
        $request['product_id'] = $product->id;
        $request['product_price'] = $product->cost_price ?? $product->supplier_price_cost;
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
        $data = $this->categoryService->list(new Request(['status' => activeType()['as']]));
        return $data;
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
    public function list(Request $request, $pagination = false, $perPage = 10, $recursiveRel = [])
    {
        $moreConditionForFirstLevel = [];
        if($request->is_mostOrder)
        {
            $moreConditionForFirstLevel = ['where' => ['saleCountProduct' => ['>', '0']]];
        }
        if($request->is_recentlyArrived)
        {
            $moreConditionForFirstLevel = ['where' => ['created_at' => ['>', Carbon::now()->subDays(30)
                ->format('Y-m-d 00:00:00')]]];
        }
        return ProductListResource::collection($this->repo->list($request, $pagination, $perPage,
            $moreConditionForFirstLevel, $recursiveRel));
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
        return new ProductListResource($this->repo->findOne($id));
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
    public function listHome(Request $request, $pagination = false, $perPage = 10)
    {
        $orderBy = [];
        $moreConditionForFirstLevel = [];
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
        $specialOffers = ProductListResource::collection($this->specialOffers($request, $moreConditionForFirstLevel,
            $orderBy));
        return [
            'specialOffers' => $specialOffers,
            'recentlyAdded' => $recentlyAdded,
            'mostOrdered' => $mostOrdered,
        ];
    }

    /**
     * It returns the result of the findBy function in the repo class
     *
     * param Request request The request object
     * param moreConditionForFirstLevel This is the condition that will be added to the first level of
     * the query.
     * param orderBy This is the order by clause.
     *
     * return An array of objects.
     */
    public function specialOffers(Request $request, $moreConditionForFirstLevel, $orderBy)
    {
        $newRequest = $request;
        $newRequest->merge(['is_discount' => 1]);
        return $this->repo->findBy($newRequest, moreConditionForFirstLevel: $moreConditionForFirstLevel,
            orderBy: $orderBy, perPage: 4, pagination: true);
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
    public function recentlyAdded(Request $request, $moreConditionForFirstLevel)
    {
        return $this->repo->findBy($request, orderBy: ['column' => 'id', 'order' => 'desc'],
            moreConditionForFirstLevel: $moreConditionForFirstLevel, perPage: 4, pagination: true);
    }

    /**
     * It returns the products that have been ordered more than 0 times
     *
     * param Request request the request object
     * param orderBy the column name to order by
     *
     * return The return value is a collection of products.
     */
    public function mostOrdered(Request $request, $orderBy)
    {
        $moreConditionForFirstLevel = ['where' => ['saleCountProduct' => ['>', '0']]];
        return $this->repo->findBy($request, moreConditionForFirstLevel: $moreConditionForFirstLevel, orderBy: $orderBy,
            perPage: 4, pagination: true);
    }

    /**
     * This PHP function downloads a file if it exists and deletes other files.
     *
     * return BinaryFileResponse|string Either a `BinaryFileResponse` object (if the file exists and
     * is successfully downloaded) or a string "not found" (if the file does not exist).
     */
    public function download()
    {
        //todo remove duplication
        if(ob_get_contents())
        {
            ob_end_clean();
        }
        $path = public_path('missings/products/' . auth()->id() . '/products_failed_rows.xlsx');
        if(file_exists($path))
        {
            return response()->download($path);
        }
        return "not found";
    }

    /**
     * It returns a list of eventd from the database.
     *
     * return The return value is an array of objects.
     */
    public function getEventsList()
    {
        return $this->eventService->findBy(new Request());
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

    public function deleteProductImage(Request $request)
    {
        return $this->repo->deleteImage($request);
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
     * The function "storeCategoryBySupplier" stores a category with the "isApproved" field set to 0.
     *
     * param request The  parameter is an object that contains the data sent by the client in
     * the HTTP request. It typically includes information such as form inputs, query parameters, and
     * request headers. In this case, it is being used to pass data to the store method of the
     * categoryService object.
     *
     * return The store() method of the categoryService is being returned.
     */
    public function storeCategoryBySupplier($request)
    {
        $request->merge([
            'isApproved' => 0,
            'commission' => 0,
            'supplier_id' => auth()->id()
        ]);
        $category = $this->categoryService->store($request);
        $title = json_encode([
            'en' => 'Supplier suggested new category',
            'ar' => "اقترح المورد فئة جديدة",
        ]);
        $content = json_encode([
            'en' => ' Supplier # ' . auth()->id() . ' Suggested new category ',
            'ar' => ' المورد # ' . auth()->id() . ' فئة جديدة مقترحة',
        ]);
        $urlType = 'supplier_suggested_new_category';
        $urlId = $category->id;
        $color = '#1E90FF';
        App(SendNotificationByAdminAction::class)->execute($title, $content, $urlType, $urlId, $color);
    }

    public function search(Request $request)
    {
        $moreConditionForFirstLevel  = [];
        if(isset($request->term) && $request->term != null)
        {
            $moreConditionForFirstLevel += ['orWhere' => ['id' => [$request->term], 'sku' => ['LIKE', '%' . $request->term . '%']]];
        }
        return $this->repo->findBy(new Request(['name'=>$request->term,'supplier_id'=>user()->id]), moreConditionForFirstLevel: $moreConditionForFirstLevel);
    }
}
