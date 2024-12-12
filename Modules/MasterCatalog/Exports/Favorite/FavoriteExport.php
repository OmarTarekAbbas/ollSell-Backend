<?php

namespace Modules\MasterCatalog\Exports\Favorite;

use Modules\MasterCatalog\Entities\Favorite;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\MasterCatalog\Service\FavoriteService;
//todo change
class FavoriteExport implements FromView
{
    /**
     * This PHP function returns a view that either downloads an Excel file based on product data or
     * displays a view of all favorites with their associated product images.
     * 
     * return View a view.
     */
    public function view(): View
    {
        $request = request();
        // 1 This is to do a test on Google Chrome, not from the postman, so I know how to download Excel
        $userId =  (user()) ? user()->id : 1;
        if ($request->has('products')) {
            return $this->downloadExcelByProducts($request, $userId);
        } else {
            $favorites = app()->make(FavoriteService::class)->findBy(new Request(['dropshipper_id' => $userId]));
            foreach ($favorites as $key => $favorite) {
                $urlProductImages = '';
                foreach ($favorite->product->logo as  $logo) {
                    $urlProductImages = $urlProductImages . ',' . url('images/product/' . $logo->category_id . '/' . $logo->file);
                }
                $favorite->url = $urlProductImages;
            }
            return view('favoriteExcel.exportAll', compact('favorites'));
        }
    }

    /**
     * It takes a request and a userId, then it takes the products from the request, then it loops
     * through the products and gets the favorites by product, then it returns the favorites
     *
     * param request the request object
     * param userId The user id of the user who is logged in.
     *
     * return The view is being returned.
     */
    public function downloadExcelByProducts($request, $userId)
    {
        $products = explode(',', $request->products);
        $favorites = [];
        foreach ($products as $key => $product) {
            $favoriteByProduct = app()->make(FavoriteService::class)->findBy(new Request(['dropshipper_id' => $userId, 'product_id' => $product]));
            $favorites[] = $favoriteByProduct;
        }
        foreach ($favorites as $key => $favorite) {
            foreach ($favorite as $favorit) {
                $urlProductImages = '';
                foreach ($favorit->product->logo as  $logo) {
                    $urlProductImages = $urlProductImages . ',' . url('images/product/' . $logo->category_id . '/' . $logo->file);
                }
                $favorite->url = $urlProductImages;
            }
        }

        return view('favoriteExcel.export', compact('favorites'));
    }
}
