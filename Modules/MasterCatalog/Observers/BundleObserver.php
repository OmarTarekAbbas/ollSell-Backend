<?php

namespace Modules\MasterCatalog\Observers;

use Modules\MasterCatalog\Entities\Bundle;
use Modules\MasterCatalog\Entities\BundleProduct;

use Modules\MasterCatalog\Service\BundleProductService;
use Illuminate\Http\Request;

class BundleObserver
{
    /**
     * Handle the Favorite "created" event.
     *
     * param  \App\Models\Favorite  $favorite
     * return void
     */
    public function created(Bundle $bundle)
    {
        $request = request();
        $counter = 0;
    
        foreach ($request->bundle_products['product_id'] as $product_id) {

            if (!empty($product_id)) {
      
                $myNEWrequest = new Request([
                    'product_id' => $product_id,
                    'count' =>  $request->bundle_products['count'][$counter],
                    'bundle_id' => $bundle->id
                ]);
           
                app()->make(BundleProductService::class)->store($myNEWrequest);
            }
            $counter++;
        }

     
    }

    /**
     * Handle the Favorite "updated" event.
     *
     * param  \App\Models\Favorite  $favorite
     * return void
     */
    public function updated(Bundle $bundle)
    {
        $request = request();
        $counter = 0;
        BundleProduct::where('bundle_id', $bundle->id)->delete();
        foreach ($request->bundle_products['product_id'] as $product_id) {

            if (!empty($product_id)) {
      
                $myNEWrequest = new Request([
                    'product_id' => $product_id,
                    'count' =>  $request->bundle_products['count'][$counter],
                    'bundle_id' => $bundle->id
                ]);
           
                app()->make(BundleProductService::class)->store($myNEWrequest);
            }
            $counter++;
        }

    }

    /**
     * Handle the Favorite "deleted" event.
     *
     * param  \App\Models\Favorite  $favorite
     * return void
     */
    public function deleted(Bundle $bundle)
    {
        BundleProduct::where('bundle_id', $bundle->id)->delete();
    }


}
