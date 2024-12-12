<?php

namespace Modules\Finance\Actions\WithdrawalRequest;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Acl\Entities\Dropshipper;
use Modules\Finance\Enums\ProfitEnum;
use Modules\Finance\Repositories\WithdrawalRequestRepository;
use Modules\Finance\Service\TransactionService;

class EarningsWithdrawalRequestAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(WithdrawalRequestRepository $repository)
    {
        $this->repo = $repository;
    }

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
    public function execute(Request $request)
    {
        $dropshipper = Dropshipper::find($request->dropshipper_id ?? user()->id);
        $dropshipper->walletBalance = $dropshipper->walletBalance + $request->amount;
        $dropshipper->earningsWithdrawal = $dropshipper->earningsWithdrawal - $request->amount;
        $dropshipper->save();
        $newRequest = new Request([ 'earning_date'=>Carbon::now(),
            'isStatus'=>ProfitEnum::WALLETE_DONE, 'earning_type' => ProfitEnum::WALLETE]);
        app()->make(TransactionService::class)->updatedTransactionStatus($newRequest,$request->order_id);
        return true;
    }
}
