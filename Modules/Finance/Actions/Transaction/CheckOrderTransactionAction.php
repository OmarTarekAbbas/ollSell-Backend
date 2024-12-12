<?php

namespace Modules\Finance\Actions\Transaction;

use Illuminate\Http\Request;
use Modules\Finance\Enums\ProfitEnum;
use Modules\Finance\Repositories\TransactionRepository;

class CheckOrderTransactionAction
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
    public function execute($dropshipper, $amount)
    {
        $transactions = $this->repo->findBy(new Request(['dropshipper_id' => $dropshipper->id, 'isStatus' => ProfitEnum::AVAILABLE]),orderBy: ['column' => 'id', 'order' => 'asc']);
        $order_id = [];
        $maxAmount = 0;
        $minAmount = 0;
        $check = false;
        foreach ($transactions as $transaction) {
            $maxAmount += $transaction->profitRatio;
            $order_id[] = $transaction->order_id;
            if ($maxAmount == $amount) {
                $check = true;
                break;
            }
            if ($maxAmount < $amount) {
                $minAmount = $maxAmount;
            }
            if ($maxAmount > $amount) {
                $check = false;
                break;
            }
        }
        return ['check' => $check, 'min_amount' => $minAmount, 'max_amount' => $maxAmount, 'order_ids' => $order_id];
    }
}
