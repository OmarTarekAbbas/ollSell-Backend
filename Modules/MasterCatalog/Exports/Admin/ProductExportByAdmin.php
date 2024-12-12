<?php

namespace Modules\MasterCatalog\Exports\Admin;


use Illuminate\Http\Request;
use Modules\Acl\Entities\Supplier;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\MasterCatalog\Service\ProductService;

class ProductExportByAdmin implements FromView
{
    //todo change
    protected $search;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * This PHP function returns a view that either downloads an Excel file based on product data or
     * displays a view of all favorites with their associated product images.
     *
     * @return View a view.
     */
    public function view(): View
    {

        // Duplicate the original request
        $originalRequest = Request::capture(); // Capture the original request
        $newRequest = $originalRequest->duplicate();


        $products = app()->make(ProductService::class)->indexExportByAdmin($newRequest);

        foreach ($products as  $product) {
            $urlProductImages = '';
            $urlProductThumbnail = '';
            $arrayIdCategories = '';
            $arrayNameCategories = '';

            foreach ($product->logo as $logo) {
                $urlProductImages .= ',' . asset('images/product/' . $logo->category_id . '/' . $logo->file);
            }
            $urlProductImages = ltrim($urlProductImages, ',');
             

            foreach ($product->thumbnail as $thumbnail) {
                $urlProductThumbnail .= ',' . asset('images/product/' . $thumbnail->category_id . '/' . $thumbnail->file);
            }
            $urlProductThumbnail = ltrim($urlProductThumbnail, ',');
            

            foreach ($product->categories as $index => $category) {
                if ($index > 0) {
                    $arrayIdCategories .= ',';
                    $arrayNameCategories .= ',';
                }

                $arrayIdCategories .= $category->id;
                $arrayNameCategories .= $category->name->value;
            }

            $product->url = $urlProductImages;
            $product->thumbnail = $urlProductThumbnail;
            $product->category_id = $arrayIdCategories;
            $product->category_name = $arrayNameCategories;
            $product->supplier_name = Supplier::find($product->supplier_id)->name ?? '';
        }
        return view('productExportByAdmin.exportAll', compact('products'));
    }
}
