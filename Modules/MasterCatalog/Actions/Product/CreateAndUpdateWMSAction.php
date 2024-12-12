<?php

namespace Modules\MasterCatalog\Actions\Product;

use App\Services\AymakanService;

class CreateAndUpdateWMSAction
{
    private $aymakanService;

    /**
     * Create a new instance of the class with dependencies injected.
     *
     * @param AymakanService $aymakanService
     */
    public function __construct(AymakanService $aymakanService)
    {
        $this->aymakanService = $aymakanService;
    }

    /**
     *  Execute the action.
     *  This method is called when the action is executed.
     * It should contain the logic to be executed
     * when the action is called.
     * @param $product object
     * @return mixed
     */
    public function create($product)
    {
        return $this->aymakanService->syncWithCreateAPI($product);
    }

    /**
     * The update function calls a method to synchronize the product data with an external API.
     * @param product The product parameter is an instance of the Product class, which likely contains
     * @return The function returns the result of the synchronization process, which is likely a boolean
     */
    public function update($product)
    {
        return $this->aymakanService->syncWithUpdateAPI($product);
    }
}
