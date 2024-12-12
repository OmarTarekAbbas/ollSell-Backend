<?php

namespace Modules\CoreData\Actions\State;

use Illuminate\Http\Request;
use Modules\CoreData\Repositories\StateRepository;

class IndexStateAction
{
    protected Request $request;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {    
        $this->request=$request;
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
    public function execute()
    {
        $tableLength = session('table_length') ?? config('app.pagination_pages');
        $moreConditionForFirstLevel  = [];
        $recursiveRel  = [];
        if (isset($this->request->search) && $this->request->search != null) {
            $moreConditionForFirstLevel  += ['orWhere' => ['id' => [$this->request->search]]];
            $recursiveRel = ['translation' =>
            [
                'type' => 'whereHas',
                'where' => ['value' => ['LIKE', '%' . $this->request->search . '%']],
            ]];
        }
        if (isset($this->request->status) && $this->request->status != "null") {
            $moreConditionForFirstLevel  += ['where' => ['status' => [$this->request->status]]];
        }
        return App(StateRepository::class)->findBy($this->request, moreConditionForFirstLevel: $moreConditionForFirstLevel, pagination: true, perPage: $tableLength, recursiveRel: $recursiveRel, orderBy: ['column' => 'id', 'order' => 'desc']);
    }
}
