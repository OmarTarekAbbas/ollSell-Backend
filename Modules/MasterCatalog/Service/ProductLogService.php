<?php

namespace Modules\MasterCatalog\Service;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\MasterCatalog\Repositories\ProductLogRepository;

class ProductLogService extends BasicService
{
    protected $repo;


    public function __construct(ProductLogRepository $repository)
    {
        $this->repo = $repository;
    }


    public function findBy(Request $request, $moreConditionForFirstLevel = [], $pagination = false, $perPage = 10,
        $orderBy = [], $recursiveRel = [], $get = '', $withRelations = [])
    {
        return $this->repo->findBy($request, $pagination, $perPage, $orderBy, $moreConditionForFirstLevel,
            $recursiveRel, $get, withRelations: $withRelations);
    }


    public function index(Request $request, $pagination = false, $perPage = 10)
    {
        $moreConditionForFirstLevel = [];
        $recursiveRel = [];
        if($request->fromDate && $request->toDate)
        {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [Carbon::parse($request->fromDate)
                ->startOfDay(), Carbon::parse($request->toDate)->endOfDay()]]];
        }
        return $this->repo->findBy(request: $request, moreConditionForFirstLevel: $moreConditionForFirstLevel,
            pagination: $pagination, perPage: $perPage, recursiveRel: $recursiveRel,
            orderBy: ['column' => 'id', 'order' => 'desc']);
    }
}
