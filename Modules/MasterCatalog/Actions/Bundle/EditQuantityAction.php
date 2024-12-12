<?php

namespace Modules\MasterCatalog\Actions\Bundle;

use Illuminate\Http\Request;
use Modules\MasterCatalog\Service\BundleService;

class EditQuantityAction
{
    /**
     * This function executes an order by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the order data.
     *
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     *
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute($product)
    {
        $bundles = App(BundleService::class)->findBy(new Request(['id' => $product->bundle->pluck('bundle_id')]));
        foreach($bundles as $bundle)
        {
            $quantity = [];
            foreach($bundle->products as $item)
            {
                $quantity[] = $item->product->quantity;
            }
            $bundle->update(['quantity' => min($quantity)]);
        }
    }
}
