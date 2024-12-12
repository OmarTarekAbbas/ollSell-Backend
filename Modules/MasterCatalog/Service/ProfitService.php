<?php

namespace Modules\MasterCatalog\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\MasterCatalog\Entities\Profit;
use Modules\MasterCatalog\Http\Resources\Profit\ProfitResource;
use Modules\MasterCatalog\Repositories\ProfitRepository;
//todo change
class ProfitService extends BasicService
{
    protected $repo;
    protected $model;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(ProfitRepository $repository, Profit $model)
    {
        $this->repo = $repository;
        $this->model = $model;
    }

    /**
     * It takes a request, passes it to the repo, and returns the result of the repo's save method.
     * 
     * param Request request The request object
     * 
     * return The data is being returned.
     */
    public function store(Request $request)
    {
        $data = $this->repo->save($request, $favorite = null);
        if ($data) {
            return new ProfitResource($data);
        }
        return false;
    }

    /**
     * It returns a collection of ProfitResource
     * 
     * param Request request This is the request object that is passed to the controller.
     * return A collection of ProfitResource
     */
    public function showProfit(Request $request)
    {
        $data =  $this->getProfit();
        if ($data) {
            return new ProfitResource($data);
        }
        return null;
    }

    /**
     * It returns the first row of the table where the column dropshipper_id is equal to the id of the
     * currently logged in user.
     * 
     * return The first row of the table where the dropshipper_id is equal to the user's id.
     */
    public function getProfit()
    {
        return user()->profit;
    }

    /**
     * It takes a favorite object and saves it to the database
     * 
     * param favorite the favorite object
     * 
     * return A ProfitResource
     */
    public function storeByFavorite($favorite)
    {
        $data = $this->repo->save(request(), $favorite);
        if ($data) {
            return new ProfitResource($data);
        }
        return null;
    }

    /**
     * It gets all the profit by dropshipper, then it loops through each profit by dropshipper and
     * saves it
     * 
     * param dropshipper the user who is a dropshipper
     */
    public function storeByUpdateProfitDropShipper($dropshipper)
    {
        $getProfitByDropShippers = $this->findBy(new Request(['dropshipper_id' => user()->id, 'is_manual' => 0]));
        $request = request();
        $request->merge(['isGeneral' => true]);
        foreach ($getProfitByDropShippers as $getProfitByDropShipper) {
            $this->repo->save($request, $getProfitByDropShipper);
        }
    }

    /**
     * It takes a request, a boolean for pagination, and a number for the number of items per page
     * 
     * param Request request The request object
     * param pagination true or false
     * param perPage The number of items to show per page.
     * 
     * return A collection of objects.
     */
    public function findBy(Request $request, $orderBy = [], $pagination = false, $perPage = 10, $get = '', $moreConditionForFirstLevel = [], $limit = null)
    {
        return $this->repo->findBy($request, $orderBy, $moreConditionForFirstLevel, $limit, $pagination,  $perPage, $get);
    }

    /**
     * The function returns a report of profits for a dropshipper based on their ID.
     * 
     * param request  is a parameter passed to the function reportProfit(). It is an instance
     * of the Request class, which is a class used to represent an HTTP request in Laravel. The
     *  parameter is used to filter the results returned by the findBy() method. In this case,
     * it filters the results to only
     * 
     * return the result of calling the `findBy` method with a new `Request` object that has a
     * `dropshipper_id` parameter set to the current user's ID.
     */
    public function reportProfit($request, $moreConditionForFirstLevel, $limit)
    {
        $orderBy = ['column' => 'profit', 'order' => 'desc'];
        return $this->findBy(new Request(['dropshipper_id' => user()->id]), orderBy: $orderBy, moreConditionForFirstLevel: $moreConditionForFirstLevel, limit: $limit);
    }
}
