<?php

namespace Modules\Setting\Service;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Setting\Repositories\RequestLogRepository;
use Modules\Basic\Service\BasicService;

class RequestLogService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(RequestLogRepository $repository,)
    {
        $this->repo = $repository;
    }

    public function indexDashboard($request)
    {
        $tableLength = session('table_length') ?? config('app.pagination_pages');
        $moreConditionForFirstLevel = [];
        if($request->fromDate && $request->toDate)
        {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [Carbon::parse($request->fromDate)
                ->startOfDay(), Carbon::parse($request->toDate)->endOfDay()]]];
        }elseif($request->fromDate)
        {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['>=', Carbon::parse($request->fromDate)
                ->startOfDay()]]];
        }elseif($request->toDate)
        {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['<=', Carbon::parse($request->toDate)
                ->endOfDay()]]];
        }
        return $this->repo->findBy($request, moreConditionForFirstLevel: $moreConditionForFirstLevel,
            pagination: true, perPage: $tableLength, orderBy: ['column' => 'id', 'order' => 'desc']);
    }

    public function store(Request $request)
    {
        $this->repo->save($request);
    }
}
