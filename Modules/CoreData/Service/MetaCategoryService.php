<?php

namespace Modules\CoreData\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Repositories\MetaCategoryRepository;
use Modules\CoreData\Actions\MetaCategory\MetaCategoryAction;

class MetaCategoryService extends BasicService
{
    protected MetaCategoryRepository $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(MetaCategoryRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function saves data from a request and returns true if successful, false otherwise.
     *
     * param Request request  is an instance of the Request class which contains the data
     * submitted through an HTTP request. 
     * return a boolean value. If the data is successfully saved, it will return true, otherwise it
     * will return false.
     */
    public function store(Request $request)
    {
        if ($this->repo->save($request)) {

            return true;
        }

        return false;
    }

    /**
     * This function stores data by category using a repository and returns true if successful.
     *
     * param category The parameter `` is being passed to the `storeByCategory` function as an
     * argument. It is not being used in the function and its purpose is not clear from the code snippet
     * provided.
     *
     * return If the data is successfully saved, the function returns `true`. Otherwise, it returns
     * `false`.
     */
    public function storeByCategory($category)
    {
        return (new MetaCategoryAction(
            request: $request,
            category:$category
         ))->execute();
    }

    /**
     * This function updates data using a repository and returns the updated data or false.
     *
     * param Request request  is an instance of the Request class which contains the data sent
     * by the client in the HTTP request. It is used to retrieve input data, such as form data or query
     * parameters, and to handle file uploads. In this case, it is being passed to the save method of a
     * repository class to
     * param id  is a parameter that represents the unique identifier of the resource being
     * updated. It is used to identify the specific resource that needs to be updated in the database.
     *
     * return If the `` variable is truthy, it will be returned. Otherwise, `false` will be
     * returned.
     */
    public function update(Request $request, $id)
    {
        if ($this->repo->save($request, $id)) {

            return $this->repo->save($request, $id);
        }

        return false;
    }
}
