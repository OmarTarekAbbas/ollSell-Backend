<?php

namespace Modules\MasterCatalog\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\MasterCatalog\Http\Requests\Product\CreateRequest;
use Modules\MasterCatalog\Http\Requests\Product\EditRequest;
use Modules\MasterCatalog\Service\ProductService;
use Maatwebsite\Excel\Facades\Excel;
use Modules\MasterCatalog\Imports\ProductImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Modules\MasterCatalog\Entities\Attribute;
use Modules\CoreData\Entities\Category;
use Modules\MasterCatalog\Entities\ProductVariant;
use Modules\MasterCatalog\Exports\Admin\ProductExportByAdmin;

class ProductController extends BasicController
{
    protected $service;

    /**
     * This function constructs a ProductService object and sets middleware permissions for various
     * actions related to product management.
     *
     * param ProductService Service The ProductService class instance that will be injected into the
     * constructor of the current class. This is likely used to perform operations related to products,
     * such as retrieving or updating them.
     */
    public function __construct(ProductService $Service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_product')->only('index');
        $this->middleware('permission:create_product')->only(['create', 'store']);
        $this->middleware('permission:update_product')->only(['edit', 'update']);
        $this->middleware('permission:delete_product')->only('destroy');
        $this->middleware('permission:extract_product')->only('extract');
        $this->service = $Service;
    }

    /**
     * This PHP function returns a view of a product table or a dashboard view depending on whether the
     * request is AJAX or not.
     *
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request such as the HTTP
     * method, URL, headers, and any data sent in the request body. In this code,  is used to
     * pass data to the index() method
     *
     * return If the request is an AJAX request, a view called 'mastercatalog::product.table' with the
     * products passed as a parameter is being returned. Otherwise, the 'mastercatalog::product.index'
     * view with the products passed as a parameter is being returned within the dashboard view.
     */
    public function index(Request $request)
    {
        if (isset($request->success) && $request->success == 'true') {
            //todo fix typo
            updateSettings('uploading_products', 'false');
        }
        $import_file = "";
        if (Storage::disk('public_missings')->has('products_importing_counts.json')) {
            $import_file = Storage::disk('public_missings')->get('products_importing_counts.json');
        }
        $products = $this->service->index($request, pagination: true, perPage: $this->perPage());
        if ($request->ajax()) {
            return view('mastercatalog::product.table')->with(['products' => $products]);
        }
        return $this->getDashboardView(
            'mastercatalog::product.index',
            ['products' => $products, 'import_file' => $import_file]
        );
    }

    /**
     * This function retrieves and returns a view with data from a JSON file and a file URL.
     *
     * return a view with the variables `` and `` passed to it. The view being
     * returned is `mastercatalog::product.importfile`.
     */
    public function importFile()
    {

        $import_file = "";
        if (Storage::disk('public_missings')->has('products_importing_counts.json')) {
            $import_file = Storage::disk('public_missings')->get('products_importing_counts.json');
        }
        $fileUrl = null;
        $files = Storage::disk('public_missings')->allFiles();
        if ($files) {
            $fileUrl = route('product.files.download');
        }
        return $this->getDashboardView('mastercatalog::product.importfile', compact('import_file', 'fileUrl'));
    }

    /**
     * This PHP function returns a view for creating a new product with categories and target markets
     * as options.
     *
     * return a view for creating a new product with the categories and target markets passed as
     * variables.
     */
    public function create()
    {
        $categories = $this->service->categoryList();
        //todo
        $attributes = Attribute::limit(3)->get();
        $events = $this->service->getEventsList();
        $warehouses = $this->service->getWarehouses();
        $warehouseIsInternal = $this->service->getWarehouseIsInternal();
        return $this->getDashboardView(
            'mastercatalog::product.create',
            compact('categories', 'attributes', 'events', 'warehouses', 'warehouseIsInternal')
        );
    }

    /**
     * This function stores data from a CreateRequest and redirects to the product index page with a
     * success or error message.
     *
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form when creating a new product. This data is validated against the rules
     * defined in the CreateRequest class before being passed to the store
     *
     * return a redirect response to either the 'product.index' route with a 'Done' message in the
     * session data if the data was successfully stored, or to the 'product.create' route with a
     * 'problem' message in the session data if there was an issue with storing the data.
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('product.index', ['is_wms' => $data->is_wms]))->with('Done');
        }
        return redirect(route('product.create'))->with('problem');
    }

    public function show($id)
    {
        $data = $this->service->show($id);

        return $this->getDashboardView('mastercatalog::product.show', get_defined_vars());
    }

    /**
     * This PHP function retrieves data and lists of categories and target markets to be used in
     * editing a product.
     *
     * param id The ID of the product that needs to be edited.
     *
     * return a view for editing a product with data, categories, and target markets passed as
     * variables to the view.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        $categories = $this->service->categoryList();
        //todo
        $attributes = Attribute::limit(3)->get();
        $events = $this->service->getEventsList();
        $warehouses = $this->service->getWarehouses();
        $warehouseIsInternal = $this->service->getWarehouseIsInternal();
        $hasVariations = $data->attributes->count() ? true : false;
        // Prepare variants data
        $variants = $data->variants_data ?? '[]';
        // Prepare attributes data
        $existingAttributes = $data->attributes_data ?? '[]';
        return $this->getDashboardView('mastercatalog::product.edit', get_defined_vars());
    }

    /**
     * This PHP function updates a product and redirects the user to the product index page with a
     * success message or back to the edit page with an error message.
     *
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a product. It is used to
     * validate the incoming request data before processing it further.
     * param id  is a parameter that represents the unique identifier of the product that needs to
     * be updated. It is used to identify the specific product record in the database that needs to be
     * updated.
     *
     * return If the update is successful, the function will return a redirect to the index page with
     * a success message. If the update fails, the function will return a redirect to the edit page
     * with a problem message.
     */
    public function update(EditRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('product.index', ['is_wms' => $data->is_wms]))->with("message", 'Done');
        }
        return redirect(route('product.edit', $id))->with("message_false", 'problem');
    }

    /**
     * This PHP function imports an Excel file and checks if the file extension is valid before
     * importing.
     *
     * param Request request  is an instance of the Illuminate\Http\Request class which
     * represents an incoming HTTP request. It contains information about the request such as the HTTP
     * method, headers, and any data that was sent with the request. In this case, it is used to
     * retrieve the uploaded file from the request.
     *
     * return either a success message if the file extension is valid and the Excel file is
     * successfully imported using the OrderImport class, or a bad request message if the file
     * extension is invalid or there is an exception thrown during the import process.
     */
    public function import(Request $request)
    {

        $extensions = ["xls", "xlsx", "csv", "xlm", "xla", "xlc", "xlt", "xlw"];
        if (!empty($request->file('excelFile'))) {
            $fileExtension = $request->file('excelFile')->getClientOriginalExtension();
            Storage::put('products_import_file.json', $request->file('excelFile')->store('files'));
            $id = now()->unix();
            $data = ["id" => $id];
            Storage::put('products_import_data.json', json_encode($data));
            if (in_array($fileExtension, $extensions)) {
                $files = public_path('/missings/products_failed_rows.xlsx');
                $files1 = public_path('/missings/products_failed_rows.json');
                $files2 = public_path('/missings/products_importing_counts.json');
                if (is_file($files)) {
                    unlink($files);
                    unlink($files1);
                    unlink($files2);
                }

                updateSettings('uploading_products', 'true');
                Excel::queueImport(new ProductImport, $request->file('excelFile'));
                return redirect(route('product.importfile'))->with('message', 'Success Upload Excel File.');
            } else {
                return redirect(route('product.importfile'))->with('message', 'Please Upload Excel File.');
            }
        }
        return redirect(route('product.importfile'))->with('message', 'Please Upload Excel File.');
    }

    /**
     * Method getDownload
     *
     * return void
     */
    public function getDownload()
    {
        $file = base_path() . "/product-excel/ProductImport.xlsx";
        $headers = [
            'Content-Type: application/xlsx',
        ];
        return response()->download($file, 'ProductImport.xlsx', $headers);
    }

    /**
     * This function calls the download method of a service object and returns its result.
     *
     * return The `download()` method of the `` object is being returned.
     */
    public function download()
    {
        return $this->service->download();
    }

    /**
     * @throws Exception
     */
    public function checkUploadingStatus()
    {
        Artisan::call('queue:work --stop-when-empty');
        $id = 0;
        if (Storage::has('import.json')) {
            $import_file = Storage::get("import.json");
            $import_file = json_decode($import_file, true);
            $id = $import_file['id'];
        }
        return response([
            'started' => filled(cache("start_date_$id")),
            'finished' => filled(cache("end_date_$id")),
            'current_row' => (int)cache("current_row_$id"),
            'total_rows' => (int)cache("total_rows_$id"),
        ]);
    }

    public function deleteImage(Request $request)
    {
        $this->service->deleteProductImage($request);
        return response()->json(['data' => 'Deleted successfully'], 200);
    }

    /**
     * The function checks if a product variant with a specific SKU exists in the database.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve data from the HTTP request. In this case, it is used to retrieve the value of the
     * 'sku' parameter from the request.
     *
     * return a boolean value. If a product variant with the specified SKU exists, it will return true.
     * Otherwise, it will return false.
     */
    public function checkVariantSku(Request $request)
    {
        $productVariant = ProductVariant::where('sku', $request->sku)->first();
        if ($productVariant) {
            return true;
        }
        return false;
    }

    /**
     * The function "listProductsSupplier" retrieves a list of products from a supplier and returns a
     * view with the products and an import file.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve information from the HTTP request made to the server. It contains data such as the
     * request method, headers, query parameters, form data, and more. In this case, it is used to check
     * if the request is
     *
     * return If the request is an AJAX request, the function will return a view called
     * 'mastercatalog::product.table' with the 'products' variable passed to it.
     */
    public function listProductsSupplier(Request $request)
    {
        if (isset($request->success) && $request->success == 'true') {
            //todo fix typo
            updateSettings('uploading_products', 'false');
        }
        $import_file = "";
        if (Storage::disk('public_missings')->has('products_importing_counts.json')) {
            $import_file = Storage::disk('public_missings')->get('products_importing_counts.json');
        }
        $products = $this->service->listProductsSupplier($request);
        if ($request->ajax()) {
            return view('mastercatalog::product.tableListProductsSupplier')->with(['products' => $products]);
        }
        return $this->getDashboardView(
            'mastercatalog::product.listProductsSupplier',
            ['products' => $products, 'import_file' => $import_file]
        );
    }

    /**
     * This function updates the isApproved field of a product to 1 and redirects the user to the
     * product index page with a success message if the update is successful, otherwise it redirects the
     * user to the product edit page with an error message.
     *
     * param Request request The  parameter is an instance of the Request class, which contains
     * all the data that was sent with the HTTP request.
     * param id The  parameter is the identifier of the supplier whose approved products are being
     * updated.
     *
     * return a redirect response. If the data is successfully updated, it will redirect to the
     * 'product.index' route with a success message. If the data update fails, it will redirect to the
     * 'product.edit' route with a problem message.
     */
    public function approvedProductsSupplier(Request $request, $id)
    {
        $data = $this->service->approvedProductsSupplier($request, $id);
        if ($data) {
            return redirect(route('product.listProductsSupplier'))->with("message", 'Done');
        }
        return redirect(route('product.approvedProductsSupplier', $id))->with('problem');
    }

    /**
     * The function "getCommissionCategory" retrieves the commission values of categories based on
     * their IDs, sorts them in descending order, and returns the highest commission value.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve data from the HTTP request. In this case, it is used to retrieve the 'ids' parameter
     * from the request.
     *
     * return the highest commission value from the categories that match the given IDs.
     */
    public function getCommissionCategory(Request $request)
    {
        $categoryIds = $request->input('categoryIds');
        //todo
        $commissions = Category::whereIn('id', $categoryIds)->pluck('commission')->toArray();
        $highestCommission = max($commissions);
        return response()->json(['highestCommission' => $highestCommission]);
    }

    /**
     * The function exports product data by an admin user in Excel format.
     *
     * return a download of an Excel file.
     */
    public function exportProductByAdmin()
    {
        return Excel::download(new ProductExportByAdmin, 'ProductExportByAdmin.xlsx');
    }

    public function search(Request $request)
    {
        return $this->service->search($request);
    }

    public function inBundleSearch(Request $request)
    {
        $allProducts = $this->service->search($request);
        // Filter out products that are associated with any bundle
        $bundledProductIds = $this->service->getBundledProductIds();
        $filteredProducts = $allProducts->filter(function($product) use ($bundledProductIds) {
            return !in_array($product->id, $bundledProductIds);
        });

        return response()->json($filteredProducts);
    }

    public function relatedProduct($id)
    {
        return $this->service->relatedProduct($id);
    }

    public function scanQuantityWms($id)
    {
        $this->service->scanQuantityWms($id);
        return redirect()->back()->with('message', 'Done');
    }

    /**
     * The function `scanProductWms` scans a product in the warehouse management system and returns the
     * dashboard view with the scanned invoices.
     * 
     * @param Request request The `Request ` parameter in the `scanProductWms` function
     * represents the incoming HTTP request that is being made to the server. It contains all the data
     * and information sent by the client, such as form inputs, headers, and other request details.
     * This parameter is typically used to extract
     * 
     * @return The `scanProductWms` function is returning a view called `order::invoice.index` with the
     * invoices data passed as a compact variable.
     */
    public function scanProductWms(Request $request)
    {
        $this->service->scanProductWms($request);
        return redirect(route('product.index', ['is_wms' => 1]))->with('message', 'Done');
    }
}
