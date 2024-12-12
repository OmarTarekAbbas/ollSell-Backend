<?php

namespace Modules\Finance\Actions\DepositRequest;

use Illuminate\Http\Request;
use Modules\Finance\Entities\DepositRequest;
use Modules\Finance\Repositories\DepositRequestRepository;
use Modules\CoreData\Actions\Notification\SendNotificationByDropshipperAction;

class RefusedDepositRequestAction
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
    public function execute(Request $request, $id)
    {
        $request->merge([
            'status' =>   DepositRequest::REJECTED_STATUS,
            'reason' =>   $request->reason,
        ]);
        $data = $this->repo->save($request, $id);

        if ($data) {

            $title = json_encode([
                'en' => 'Rejected',
                'ar' => 'مرفوض',
            ]);
            $content = json_encode([
                'en' => 'Deposit amount rejected.',
                'ar' => 'تم رفض مبلغ الإيداع.',
            ]);

            $urlType = 'depositRequest';
            $urlId = $data->id;
            $color = '#FF0000';
            
            App(SendNotificationByDropshipperAction::class)->execute($title, $content, $data->dropshipper_id, $urlType, $urlId, $color);
        }

        return true;
    }
}
