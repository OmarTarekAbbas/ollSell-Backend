<?php

namespace Modules\Finance\Actions\DepositRequest;

use Illuminate\Http\Request;
use Modules\Finance\Entities\DepositRequest;
use Modules\Finance\Repositories\DepositRequestRepository;
use Modules\CoreData\Actions\Notification\SendNotificationByAdminAction;

class StoreDepositRequestAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(DepositRequestRepository $repository)
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
    {//todo change
        $request->merge([
            'dropshipper_id' => user()->id,
            'amount' => $request->amount,
            'status' => DepositRequest::PENDING_STATUS,
        ]);

        $withdrawalRequestService = $this->repo->save($request);

        if ($withdrawalRequestService) {
            $title = json_encode([
                'en' => 'Amount has been Deposited',
                'ar' => 'تم إيداع المبلغ',
            ]);
            $content = json_encode([
                'en' => user()->id . ' by ' . $request->amount . ' amount has been deposited ',
                'ar' => user()->id . ' بواسطة ' . $request->amount. "تم إيداع المبلغ",
            ]);
            
            $urlType = 'depositRequest';
            $urlId = $withdrawalRequestService->id;
            $color = '#1E90FF';

            App(SendNotificationByAdminAction::class)->execute($title, $content, $urlType, $urlId, $color);

            return true;
        }

        return false;
    }
}
