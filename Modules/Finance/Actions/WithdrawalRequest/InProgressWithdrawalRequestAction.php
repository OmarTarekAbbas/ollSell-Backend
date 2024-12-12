<?php

namespace Modules\Finance\Actions\WithdrawalRequest;

use Illuminate\Http\Request;
use Modules\Acl\Service\DropshipperService;
use Modules\CoreData\Actions\Notification\SendNotificationByDropshipperAction;
use Modules\Finance\Entities\WithdrawalRequest;
use Modules\Finance\Enums\ProfitEnum;
use Modules\Finance\Repositories\WithdrawalRequestRepository;
use Modules\Finance\Service\TransactionService;

class InProgressWithdrawalRequestAction
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
    public function execute(Request $request, $id)
    {
        $request->merge([
            'status' =>   WithdrawalRequest::INPROGRESS_STATUS,
            'reason' =>   $request->reason,
        ]);
        $data = $this->repo->save($request, $id);

        if ($data) {

            $title = json_encode([
                'en' => 'في تَقَدم',
                'ar' => 'inProgress',
            ]);
            $content = json_encode([
                'en' => 'Deposit  inProgress',
                'ar' => 'إيداع في التقدم',
            ]);

            $urlType = 'depositRequest';
            $urlId = $data->id;
            $color = '#FF0000';
        }
        App(SendNotificationByDropshipperAction::class)->execute($title, $content, $data->dropshipper_id, $urlType, $urlId, $color);
        return true;
    }
}
