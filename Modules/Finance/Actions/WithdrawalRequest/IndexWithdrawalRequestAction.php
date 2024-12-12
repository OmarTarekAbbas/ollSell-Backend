<?php

namespace Modules\Finance\Actions\WithdrawalRequest;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Finance\Repositories\WithdrawalRequestRepository;

class IndexWithdrawalRequestAction
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
     *           will return false.
     */
    public function execute(Request $request, $pagination, $perPage)
    {
        $moreConditionForFirstLevel = [];

        if ($request->fromDate && $request->toDate) {

            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [Carbon::parse($request->fromDate)->startOfDay(),  Carbon::parse($request->toDate)->endOfDay()]]];
        }
        $recursiveRel = [];
        if ($request->search) {
            $recursiveRel = [
                'dropshipper' => ['type' => 'whereHas',
                    'whereCustom' => [
                        'orWhere' => [
                            ['id' => ['LIKE', '%'.$request->search.'%']], ['phone' => ['LIKE', '%'.$request->search.'%']], ['first_name' => ['LIKE', '%'.$request->search.'%']], ['second_name' => ['LIKE', '%'.$request->search.'%']],
                        ]]],
            ];
        }
        return $this->repo->list($request, $pagination, $perPage, moreConditionForFirstLevel: $moreConditionForFirstLevel, withRelations : ['dropshipper.transaction'], recursiveRel: $recursiveRel);
    }
}