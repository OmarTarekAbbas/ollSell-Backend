<?php

namespace Modules\CoreData\Actions\OnboardingCategory;

use Illuminate\Http\Request;
use Modules\CoreData\Repositories\OnboardingCategoryRepository;

class IndexOnboardingCategoryAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OnboardingCategoryRepository $repository)
    {
        //todo change
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
        $tableLength = session('table_length') ?? config('app.pagination_pages');
        $moreConditionForFirstLevel  = [];
        $recursiveRel  = [];

        if (isset($request->search) && $request->search != null) {

            $moreConditionForFirstLevel  += ['orWhere' => ['id' => [$request->search]]];
            $recursiveRel = ['translation' =>
            [
                'type' => 'whereHas',
                'where' => ['value' => ['LIKE', '%' . $request->search . '%']],
            ]];
        }
        
        if (isset($request->status) && $request->status != "null") {
            
            $moreConditionForFirstLevel  += ['where' => ['status' => [$request->status]]];
        }

        return $this->repo->findBy($request, moreConditionForFirstLevel: $moreConditionForFirstLevel, pagination: true, perPage: $tableLength, recursiveRel: $recursiveRel, orderBy: ['column' => 'id', 'order' => 'desc']);        
    }
}
