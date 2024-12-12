<?php

namespace Modules\Finance\Actions\Transaction;

use Modules\Finance\Enums\ProfitEnum;
use Carbon\Carbon;
use Modules\Finance\Entities\Transaction;
use Modules\Finance\Repositories\TransactionRepository;

class PaymentProfitTransactionAction
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
     * param request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request.
     *
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute($request)
    {
        $transaction = Transaction::where('dropshipper_id', dropshipperAuth()->id);
        $status = $request->isStatus == 1 ? ProfitEnum::AVAILABLE : ProfitEnum::PENDING;
        $getTables = $transaction->where('isStatus', $status)->get();

        $arrayBalanceDetailsTables = [];
        foreach($getTables as $value)
        {
            $arrayBalanceDetailsTables[] = [
                'id' => $value->order_id,
                'subTotal' => number_format($value->sellingPrice, 2),
                'grandTotal' => number_format($value->totalOrder, 2),
                'deliveryDate' => $value->order->deliveryDate,
                'readyRedeem' => Carbon::parse($value->order->deliveryDate)->addDays(7)->format('Y-m-d'),
                'Profit' => number_format($value->profitRatio, 2),
            ];
        }
        return [
            'walletBalance'=> number_format(user()->walletBalance, 2),
            'profitBalance'=> number_format(user()->profitBalance, 2),
            'earningsWithdrawalBalance' => number_format(user()->earningsWithdrawal, 2),
            'pendingBalance' => number_format($transaction->where('isStatus', ProfitEnum::PENDING)
                ->sum('profitRatio'), 2),
            'balanceDetailsTables' => $arrayBalanceDetailsTables,
        ];
    }
}
