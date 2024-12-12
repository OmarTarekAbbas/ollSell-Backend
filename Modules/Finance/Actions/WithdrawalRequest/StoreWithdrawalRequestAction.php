<?php

namespace Modules\Finance\Actions\WithdrawalRequest;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Acl\Service\DropshipperService;
use Modules\CoreData\Actions\Notification\SendNotificationByAdminAction;
use Modules\Finance\Entities\WithdrawalRequest;
use Modules\Finance\Enums\ProfitEnum;
use Modules\Finance\Repositories\WithdrawalRequestRepository;
use Modules\Finance\Service\TransactionService;

class StoreWithdrawalRequestAction
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
        $orders = $request->order_id;
        $request->merge([
            'status' => WithdrawalRequest::PENDING_STATUS,
            'dropshipper_id' => dropshipperAuth()->id,
            'total_amount_dropshipper' => dropshipperAuth()->earningsWithdrawal,
            'withdraw_dropshipper' => $request->amount,
            'balance_dropshipper' => dropshipperAuth()->earningsWithdrawal - $request->amount,
            'order_id' => json_encode($request->order_id)
        ]);
        $withdrawalRequestService = $this->repo->save($request);
        if ($withdrawalRequestService) {
            app()->make(DropshipperService::class)->withdrawalBalanceByApproved($withdrawalRequestService);
            $newRequest = new Request([
                'withdrawal_request_id' => $withdrawalRequestService->id,
                'earning_date' => Carbon::now(),
                'isStatus' => ProfitEnum::WITHDRAWAL_PENDING,
                'earning_type' => ProfitEnum::WITHDRAWAL
            ]);
            app()->make(TransactionService::class)->updatedTransactionStatus($newRequest, $orders);
            $title = json_encode([
                'en' => 'New withdrawal Request #' . $withdrawalRequestService->id,
                'ar' => "طلب سحب جديد  #" . $withdrawalRequestService->id,
            ]);

            $content = json_encode([
                'en' => ' New withdrawal Request with id # ' . $withdrawalRequestService->id . ' has been created.',
                'ar' => " طلب سحب جديد برقم # " . $withdrawalRequestService->id . ' تم أنشاء .',
            ]);

            $urlType = 'withdrawalRequestService';
            $urlId = $withdrawalRequestService->id;
            $color = '#FFA07A';

            App(SendNotificationByAdminAction::class)->execute($title, $content, $urlType, $urlId, $color);

            return true;
        }
        return false;
    }
}
