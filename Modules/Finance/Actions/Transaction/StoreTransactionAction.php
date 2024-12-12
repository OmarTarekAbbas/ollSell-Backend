<?php

namespace Modules\Finance\Actions\Transaction;

use Modules\Finance\Repositories\TransactionRepository;

class StoreTransactionAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(TransactionRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function executes an order by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the order data.
     *
     * param order order The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     *
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute($order)
    {
        $request = request();
        $request->merge([
            'paymentMethod' => $order->paymentMethod,
            'totalOrder' => $order->grandTotal,
            'costPrice' => $order->costPrice,
            'sellingPrice' => $order->subTotal,
            'profitRatio' => $order->net_profit,
            'order_id' => $order->id,
            'dropshipper_id' => $order->dropshipper_id,
        ]);

        return $this->repo->save($request);
    }
}
