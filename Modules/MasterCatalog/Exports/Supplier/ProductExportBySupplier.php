<?php

namespace Modules\MasterCatalog\Exports\Supplier;


use Modules\Acl\Entities\Supplier;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\MasterCatalog\Service\ProductService;
//todo change
class ProductExportBySupplier implements FromView
{
    protected $search;
    protected $supplier_id;

    public function __construct($search, $supplier_id)
    {
        $this->search = $search;
        $this->supplier_id = $supplier_id;
    }

    /**
     * This PHP function returns a view that either downloads an Excel file based on product data or
     * displays a view of all favorites with their associated product images.
     *
     * @return View a view.
     */
    public function view(): View
    {
        $request = request();

        $products = app()->make(ProductService::class)->exportForSupplier($request->merge(['supplier_id' => $this->supplier_id, 'search' => $this->search]));
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
        return view('productExportBySupplier.exportAll', compact('products'));
    }
}
