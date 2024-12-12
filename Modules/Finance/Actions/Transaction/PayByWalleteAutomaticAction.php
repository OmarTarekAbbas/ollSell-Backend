<?php

namespace Modules\Finance\Actions\Transaction;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Acl\Entities\Dropshipper;
use Modules\Finance\Actions\WithdrawalRequest\EarningsWithdrawalRequestAction;
use Modules\Finance\Enums\ProfitEnum;
use Modules\Finance\Repositories\TransactionRepository;

class PayByWalleteAutomaticAction
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
        $dropshipper = Dropshipper::find($order->dropshipper_id);
        $transactions = $this->repo->findBy(new Request(['dropshipper_id' => $order->dropshipper_id, 'isStatus' => ProfitEnum::AVAILABLE]));
        $order_id = [];
        $wallet = $dropshipper->walletBalance;
        $newAmount = 0;
        $check = false;
        $amount = $order->grandTotal;
        if ($wallet >= $amount) {
            $check = true;
        } else {
            foreach ($transactions as $transaction) {
                $wallet += $transaction->profitRatio;
                $newAmount += $transaction->profitRatio;
                $order_id[] = $transaction->order_id;
                if ($wallet >= $amount) {
                    $check = true;
                    break;
                }
            }
        }
        if ($check) {
            if (count($order_id)) {
                $newRequest = new Request(['dropshipper_id' => $order->dropshipper_id, 'amount' => $newAmount, 'order_id' => $order_id]);
                return  app()->make(EarningsWithdrawalRequestAction::class)->execute($newRequest);
            }
            return true;
        } else {
            return false;
        }
    }
}
