<?php

namespace Modules\MasterCatalog\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\MasterCatalog\Entities\Product;
use Modules\MasterCatalog\Http\Resources\Product\ProductResource;
use Modules\MasterCatalog\Service\ProductService;
use ZipArchive;

/**
 * @group Product management
 *
 * APIs for managing products
 */
class ProductController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that requires authentication for a dropshipper and initializes a
     * ProductService object.
     *
     * param ProductService Service The `` parameter is an instance of the `ProductService`
     * class, which is likely a service class responsible for handling business logic related to
     * products in the application. The constructor is injecting this service into the controller,
     * allowing the controller to use its methods and functionality.
     */
    public function __construct(ProductService $Service)
    {
        $this->middleware('auth:dropshipper');
        $this->service = $Service;
    }

    /**
     * List Products
     *
     * The List Products endpoint allows users to retrieve a list of products available within the system.
     * This endpoint provides users with information about the products offered by the platform,
     * filtered by the user's target market.
     *
     * This endpoint retrieves the list of products available within the system,
     * filtered by the user's target market. The API will respond with the product
     * information, including the product name, description, price, and any other relevant details.
     */
    public function list(Request $request)
    {
        $request->merge(['isApproved' => 1, 'orderBy' => ['column' => 'quantity', 'order' => 'desc']]);
        return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage()));
    }

    public function categoryProductList(Request $request)
    {
        $request->merge(['isApproved' => 1, 'orderBy' => ['column' => 'quantity', 'order' => 'desc']]);
        return $this->apiResponse($this->service->categoryProductList($request, $this->pagination(), $this->perPage()));
    }

    /**
     * Get Product
     *
     * The Get Product endpoint allows users to retrieve detailed information about a specific product
     * within the system. This endpoint provides users with comprehensive details about a particular
     * product based on its unique identifier.
     *
     * This endpoint retrieves the detailed information about a specific product based on its ID.
     * The API will respond with the product details, including the product name, description, price,
     * and any other relevant information.
     */
    public function show($id)
    {
        $product = $this->service->show($id);
        if($product && $product->isApproved)
        {
            return $this->apiResponse(new ProductResource($product));
        }else
        {
            return $this->notFoundResponse('Product not found', 404);
        }
    }

    public function recentlList(Request $request)
    {
        return $this->apiResponse($this->service->recentlList($request, $this->pagination(), $this->perPage()));
    }

    public function categoryProducts(Request $request)
    {
        $request->merge(['orderBy' => ['column' => 'quantity', 'order' => 'desc']]);
        return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage()));
    }

    public function downloadMediaZip(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $mediaFiles = $product->media;
        $tempDir = public_path('temp'. DIRECTORY_SEPARATOR .  $product->id  );
        if(!file_exists($tempDir))
        {
            mkdir($tempDir, 0755, true);
        }

        // Create a unique filename for the ZIP file
        $zipFileName = 'media.zip';
        $zipFilePath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;
        if (file_exists($tempDir . DIRECTORY_SEPARATOR . $zipFileName))
        {
            $files = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;
            unlink($files);
        }
        $zip = new ZipArchive;
        if($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true)
        {
            return response()->json(['error' => 'Could not create ZIP file'], 500);
        }
        foreach($mediaFiles as $media)
        {
            // $imagePath = public_path('images/product/1/1_0.png');
            $imagePath = public_path('images/product/' . $product->id . '/' . $media->file);
            if(file_exists($imagePath))
            {
                $zip->addFile($imagePath, basename($imagePath));
            }else
            {
                \Log::error('File not found: ' . $imagePath);
            }
        }
        // Close the archive
        if(!$zip->close())
        {
            \Log::error('Failed to close ZIP archive at: ' . $zipFilePath);
            return response()->json(['status'=> 0 ,'error' => 'Could not close ZIP file'], 500);
        }
        if(!file_exists($zipFilePath))
        {
            return response()->json(['status'=> 0 ,'error' => 'ZIP file not created'], 500);
        }
        return  response()->json(['status'=> 1 ,'url' => asset('temp'. DIRECTORY_SEPARATOR .  $product->id. DIRECTORY_SEPARATOR .$zipFileName )], 200);
    }

    private function deleteDirectory($directoryPath)
    {
        if(file_exists($directoryPath))
        {
            foreach(glob($directoryPath . DIRECTORY_SEPARATOR . '*') as $file)
            {
                if(is_dir($file))
                {
                    $this->deleteDirectory($file);
                }else
                {
                    unlink($file);
                }
            }
            rmdir($directoryPath);
        }
    }
}