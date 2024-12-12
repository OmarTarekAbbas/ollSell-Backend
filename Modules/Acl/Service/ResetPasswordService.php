<?php

namespace Modules\Acl\Service;

use Illuminate\Http\Request;
use Modules\Acl\Repositories\ResetPasswordRepository;
use Modules\Basic\Service\BasicService;

class ResetPasswordService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(ResetPasswordRepository $repository)
    {
        $this->repo = $repository;
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
    public function findBy(Request $request,  $pagination = false, $perPage = 10, $get = '',)
    {
        return $this->repo->findBy($request, $pagination,  $perPage, $get);
    }
}
