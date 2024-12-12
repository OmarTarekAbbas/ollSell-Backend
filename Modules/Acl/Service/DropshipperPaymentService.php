<?php

namespace Modules\Acl\Service;

use Illuminate\Http\Request;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperPaymentResource;
use Modules\Acl\Repositories\DropshipperPaymentRepository;
use Modules\Basic\Service\BasicService;

class DropshipperPaymentService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(DropshipperPaymentRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * The function saves a request, generates a token, sends an email, and returns a resource.
     *
     * param Request request  is an instance of the Request class which contains the data
     * submitted by the user through a form or an API request. It contains information such as the HTTP
     * method, headers, and the data submitted in the request body. In this case, it is being used to
     * save the data submitted by the user
     *
     * return If the `` is successfully saved, a new `DropshipperResource` instance is returned.
     * If not, `false` is returned.
     */
    public function store(Request $request)
    {
        $request->merge(['dropshipper_id' => user()->id]);
        $data = $this->repo->save($request);
        if($data)
        {
            return new DropshipperPaymentResource($data);
        }
        return false;
    }

    /**
     * It deletes the user's account
     *
     * param Request request The request object
     *
     * return The data is being returned.
     */
    public function destroy(Request $request, $id = null)
    {
        $data = $this->repo->delete($request->id);
        if($data)
        {
            return $data;
        }
        return false;
    }

    /**
     * This function updates a dropshipper's information and returns a DropshipperResource object if
     * successful.
     *
     * param Request request  is an instance of the Request class which contains the data sent
     * by the client in the HTTP request. It can contain data from the request body, query parameters,
     * headers, etc. In this case, it is being used to update a Dropshipper resource.
     *
     * return If the `` variable is truthy, an instance of `DropshipperResource` containing the
     * updated data is returned. Otherwise, `false` is returned.
     */
    public function update(Request $request, $id = null)
    {
        $data = $this->repo->save($request, $request->id);
        if($data)
        {
            return new DropshipperPaymentResource($data);
        }
        return false;
    }

    public function list(Request $request, $pagination = true, $perPage = 10)
    {
        return DropshipperPaymentResource::collection($this->repo->findBy(new Request(['dropshipper_id' => user()->id]),
            $pagination, $perPage));
    }

    public function isMain($id)
    {
        $payment = $this->repo->findOne($id);
        if($payment)
        {
            $payments = $this->repo->findBy(new Request(['dropshipper_id' => user()->id]));
            if($payments->count())
            {
                foreach($payments as $p)
                {
                    $this->repo->save(new Request(['is_main' =>0]),$p->id);
                }
            }
            $this->repo->save(new Request(['is_main' =>1]),$id);
            return true;
        }
        return false;
    }
}
