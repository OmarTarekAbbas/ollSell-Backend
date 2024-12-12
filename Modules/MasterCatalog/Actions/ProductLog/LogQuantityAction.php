<?php

namespace Modules\MasterCatalog\Actions\ProductLog;

use Modules\MasterCatalog\Service\ProductLogService;

class LogQuantityAction
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
    public function execute($request)
    {
        App(ProductLogService::class)->store($request);
    }
}
