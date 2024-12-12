<?php

namespace Modules\MasterCatalog\Service;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Basic\Entities\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Entities\Category;
use Modules\Supplier\Entities\FileProduct;
use Modules\MasterCatalog\Entities\Product;
use Modules\CoreData\Service\CategoryService;
use Modules\Supplier\Service\WarehouseService;
use Modules\CoreData\Service\TargetMarketService;
use Modules\MasterCatalog\Exports\Product\ProductExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Modules\MasterCatalog\Repositories\ProductRepository;
use Modules\MasterCatalog\Exports\Product\FailedProductExport;
use Modules\MasterCatalog\Http\Resources\Product\ProductListResource;
//todo change
class ImportProductService extends BasicService
{
    protected $repo;
    protected CategoryService $categoryService;
    protected TargetMarketService $targetMarketService;
    protected AttributeService $attributeService;
    protected ProductVariantService $productVariantService;
    protected EventService $eventService;
    protected WarehouseService $warehouseService;


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

    ) {
        $this->repo = $repository;
        $this->categoryService = $categoryService;
        $this->targetMarketService = $targetMarketService;
        $this->attributeService = $attributeService;
        $this->productVariantService = $productVariantService;
        $this->eventService = $eventService;
        $this->warehouseService = $warehouseService;
    }


    /**
     * The function imports orders with customer and product information, calculates total prices, and
     * saves them to the database.
     *
     * param rows An array of rows containing order information to be imported. Each row is an array
     * of values representing customer name, customer phone, customer address, city, country, items,
     * and payment method.
     */
    public function importV0($rows)
    {
       
        $importing_counts = [];
        $importing_counts['failed'] = 0;
        $failed_rows = [];

        if (Storage::has('products_failed_rows.json')) {
            $failed_rows = json_decode(Storage::get('products_failed_rows.json'), true);
        }
        if (Storage::has('products_importing_counts.json')) {
            $importing_counts = json_decode(Storage::get('products_importing_counts.json'), true);
        }
        $request = request();

        foreach ($rows as $index => $row) {
            ini_set('max_execution_time', 120000);

            if ($row[0] === null || $row[1] === null || $row[2] === null || $row[3] === null || $row[4] === null || $row[5] === null || $row[6] === null || $row[7] === null || $row[8] === null) {
                $importing_counts['failed']++;
                $failed_rows[] = $row;
                Storage::disk('public_missings')->put('products_failed_rows.json', json_encode($failed_rows));
                Storage::disk('public_missings')->put('products_importing_counts.json', json_encode($importing_counts));
                continue;
            }

            if (strlen($row[0]) < 2 || strlen($row[1]) < 2) {
                $importing_counts['failed']++;
                $failed_rows[] = $row;
                Storage::disk('public_missings')->put('products_importing_counts.json', json_encode($importing_counts));
                Storage::disk('public_missings')->put('products_failed_rows.json', json_encode($failed_rows));
                continue;
            }

            $product = Product::where('sku',  $row[5])->first();
            if ($product) {
                $importing_counts['failed']++;
                $failed_rows[] = $row;
                Storage::disk('public_missings')->put('products_importing_counts.json', json_encode($importing_counts));
                Storage::disk('public_missings')->put('products_failed_rows.json', json_encode($failed_rows));
                continue;
            }

            if (is_numeric($row[2])) {
                $categoryId = $row[2];
            } else {
                $request->merge(['name' =>  $row[2]]);
                $category = $this->categoryService->findBy($request, get: "first");
                if (!$category) {
                    $importing_counts['failed']++;
                    $failed_rows[] = $row;
                    Storage::disk('public_missings')->put('products_importing_counts.json', json_encode($importing_counts));
                    Storage::disk('public_missings')->put('products_failed_rows.json', json_encode($failed_rows));
                    continue;
                }
                $categoryId = $category['id'];
            }

            // if (is_numeric($row[9])) {
            //     $targetMarketId = $row[9];
            // } else {
            //     $request->merge(['name' =>  $row[9]]);
            //     $targetMarket = $this->targetMarketService->findBy($request, get: "first");
            //     $targetMarketId = $targetMarket['id'];
            // }

            $category = Category::where('id', $categoryId)->first();
            if (!$category) {
                $importing_counts['failed']++;
                $failed_rows[] = $row;
                Storage::disk('public_missings')->put('products_importing_counts.json', json_encode($importing_counts));
                Storage::disk('public_missings')->put('products_failed_rows.json', json_encode($failed_rows));
                continue;
            }

            $request->merge([
                'name' => [
                    'en' => $row[0],
                    'ar' => $row[0],
                ],
                'description' => [
                    'en' => $row[1],
                    'ar' => $row[1],
                ],
                'sku' => $row[5],
                'cost_price' => $row[6],
                'quantity' => $row[7],
                'weight' => $row[8],
                'selling_price' => 0,
                // 'category_id' => $categoryId,
                'target_market_id' => 3,
                'size' => 1,
                'is_recommended' => 1,
                'is_discount' => 0,
                'priceAfterDiscount' =>  0,
                'status' => 0,
            ]);

            try {
                DB::beginTransaction();
                $product = $this->repo->save($request);
                $images = explode(',', $row[4]);
                $images = str_replace(' ', '', $images);
                foreach ($images as $array => $image) {
                    $fileName = $product->id . '_' . $array . '.png';
                    $destinationPath = public_path('images' . DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . $product->id . DIRECTORY_SEPARATOR . $fileName);

                    if (!file_exists(public_path('images' . DIRECTORY_SEPARATOR . 'product'))) {
                        mkdir(public_path('images' . DIRECTORY_SEPARATOR . 'product'), 0755, true);
                    }

                    if (!file_exists(public_path('images' . DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . $product->id))) {
                        mkdir(public_path('images' . DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . $product->id), 0755, true);
                    }

                    // Download the image
                    file_put_contents($destinationPath, file_get_contents($image));

                    Media::create([
                        'category_type' => 'Modules\MasterCatalog\Entities\Product',
                        'category_id' => $product->id,
                        'file' => $fileName,
                        'type' => 'logo'
                    ]);
                }
                DB::commit();
            } catch (\Exception $e) {
                $importing_counts['failed']++;
                $row['error_message'] = 'Error Message : Image not found';
                $failed_rows[] = $row;
                DB::rollBack();
            }
        }
        if ($importing_counts['failed'] > 0) {
            Excel::store(
                new ProductExport($failed_rows),
                "products_failed_rows.xlsx",
                'public_missings'
            );
        }
    }
    /**
     * The function imports orders with customer and product information, calculates total prices, and
     * saves them to the database.
     *
     * param rows An array of rows containing order information to be imported. Each row is an array
     * of values representing customer name, customer phone, customer address, city, country, items,
     * and payment method.
     */
    public function importV1($rows)
    {
        $isSupplier = (request()->url() == url('supplier/product/import'));
        $importing_counts = [];
        $importing_counts['failed'] = 0;
        $failed_rows = [];

        if (Storage::has('products_failed_rows.json')) {
            $failed_rows = json_decode(Storage::get('products_failed_rows.json'), true);
        }
        if (Storage::has('products_importing_counts.json')) {
            $importing_counts = json_decode(Storage::get('products_importing_counts.json'), true);
        }
        $request = request();
        foreach ($rows as $index => $row) {
            $sku = $this->generateRandomCode(10);
            if ($index) {
                ini_set('max_execution_time', 120000);

                if ($row[2] === null || $row[3] === null || $row[4] === null || $row[5] === null || $row[7] === null || $row[9] === null) {
                    $importing_counts['failed']++;

                    $missingColumns = [];

                    if ($row[2] === null) {
                        $missingColumns[] = 'Title';
                    }
                    if ($row[3] === null) {
                        $missingColumns[] = 'Description';
                    }
                    if ($row[4] === null) {
                        $missingColumns[] = 'Link';
                    }
                    if ($row[5] === null) {
                        $missingColumns[] = 'Image Link';
                    }
                    if ($row[7] === null) {
                        $missingColumns[] = 'Price';
                    }
                    if ($row[9] === null) {
                        $missingColumns[] = 'Category';
                    }

                    // Construct the error message with the list of missing columns
                    $errorMessage = 'Required field(s) is empty: ' . implode(', ', $missingColumns);

                    // Store the error message in the row
                    $row['error_message'] = $errorMessage;

                    // Add the row to the failed rows array
                    $failed_rows[] = $row;

                    // Store the updated failed rows and importing counts
                    Storage::disk('public_missings')->put('products_failed_rows.json', json_encode($failed_rows));
                    Storage::disk('public_missings')->put('products_importing_counts.json', json_encode($importing_counts));

                    continue;
                }


                if (strlen($row[2]) < 2) {
                    $importing_counts['failed']++;
                    $row['error_message'] = 'Error Message : Title Is less than 2 chars';
                    $failed_rows[] = $row;
                    Storage::disk('public_missings')->put('products_importing_counts.json', json_encode($importing_counts));
                    Storage::disk('public_missings')->put('products_failed_rows.json', json_encode($failed_rows));
                    continue;
                }

                $product = Product::where('sku',  $sku)->first();
                if ($product) {
                    $importing_counts['failed']++;
                    $row['error_message'] = 'Error Message : This SKU already exists'; // Include the actual error message
                    $failed_rows[] = $row;
                    Storage::disk('public_missings')->put('products_importing_counts.json', json_encode($importing_counts));
                    Storage::disk('public_missings')->put('products_failed_rows.json', json_encode($failed_rows));
                    continue;
                }
                $categoryId = null;
                if ($row[9] != null) {
                    $category = $this->categoryService->findOrCreate($row[9]);

                    if (!$category) {
                        $importing_counts['failed']++;
                        $row['error_message'] = 'Category doesnt exist';
                        $failed_rows[] = $row;
                        Storage::disk('public_missings')->put('products_importing_counts.json', json_encode($importing_counts));
                        Storage::disk('public_missings')->put('products_failed_rows.json', json_encode($failed_rows));
                        continue;
                    }
                    $categoryId = $category['id'];
                }

                if ($row[18] == 'out of stock') {
                    $quantity = 0;
                } else {
                    $quantity = $row[18];
                }

                $request->merge([
                    'name' => [
                        'en' => $row[2],
                        'ar' => $row[2],
                    ],
                    'description' => [
                        'en' => $row[3],
                        'ar' => $row[3],
                    ],
                    'sku' => $sku,
                    'cost_price' => $isSupplier ? null : str_replace('sar', '', strtolower($row[7])),
                    'supplier_price_cost' => $isSupplier ? str_replace('sar', '', strtolower($row[7])) : null,
                    'price_after_discount' => isset($row[8]) ? str_replace('sar', '', strtolower($row[8])) : null,
                    'quantity' => $quantity,
                    'weight' => str_replace('kg', '', strtolower($row[48])),
                    'selling_price' => $row[8],
                    // 'category_id' => $categoryId ?? 1, // Recheck
                    'target_market_id' => 3,
                    'size' => 1,
                    'is_recommended' => 1,
                    'isApproved' => $isSupplier ? 0 : 1,
                    'is_discount' => 0,
                    'priceAfterDiscount' =>  0,
                    'status' => 0,
                ]);

                try {
                    DB::beginTransaction();
                    $product = $this->repo->save($request);
                    $images = explode(',', $row[5]);
                    $images = str_replace(' ', '', $images);
                    foreach ($images as $array => $image) {
                        $fileName = $product->id . '_' . $array . '.png';
                        $destinationPath = public_path('images' . DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . $product->id . DIRECTORY_SEPARATOR . $fileName);

                        if (!is_dir(public_path('images' . DIRECTORY_SEPARATOR . 'product'))) {
                            mkdir(public_path('images' . DIRECTORY_SEPARATOR . 'product'), 0755, true);
                        }

                        if (!is_dir(public_path('images' . DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . $product->id))) {
                            mkdir(public_path('images' . DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . $product->id), 0755, true);
                        }

                        // Download the image
                        file_put_contents($destinationPath, file_get_contents($image));

                        Media::create([
                            'category_type' => 'Modules\MasterCatalog\Entities\Product',
                            'category_id' => $product->id,
                            'file' => $fileName,
                            'type' => 'logo'
                        ]);
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    $importing_counts['failed']++;
                    $row['error_message'] = 'Error Message : ' . $e->getMessage(); // Include the actual error message
                    $failed_rows[] = $row;
                    DB::rollBack();
                }
            }
        }

        if ($importing_counts['failed'] > 0) {
            Excel::store(
                new FailedProductExport($failed_rows),
                "products_failed_rows.xlsx",
                'public_missings'
            );
        }

        if (request()->url() == url('supplier/product/import')) {
            $count = (count($rows) - 1);
            $fileProduct = new FileProduct();
            $fileProduct->file = request()->path;
            $fileProduct->supplier_id = auth()->id();
            $fileProduct->count = $count;
            $fileProduct->countSuccess = ($count - $importing_counts['failed']);
            $fileProduct->countFail = $importing_counts['failed'];
            $fileProduct->save();
        }
    }

    function generateRandomCode(int $length)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $randomCode = '';

        for ($i = 0; $i < $length; $i++) {
            $randomCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomCode;
    }
}
