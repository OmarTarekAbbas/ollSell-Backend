<?php

namespace Modules\Basic\Service;

use Illuminate\Http\Request;
use Modules\Basic\Http\Resources\CustomTranslation\CustomTranslationListResource;
use Modules\Basic\Http\Resources\CustomTranslation\CustomTranslationResource;
use Modules\Basic\Repositories\CustomTranslationRepository;

class CustomTranslationService extends BasicService
{
    protected $repo;

    /**
     * This is a constructor function that initializes an object with a CustomTranslationRepository
     * dependency.
     * 
     * param CustomTranslationRepository repository The parameter "repository" is an instance of the
     * class "CustomTranslationRepository". It is being injected into the constructor of another class,
     * which means that the class that is receiving this parameter is dependent on the
     * "CustomTranslationRepository" class. This is an example of dependency injection, which is a
     * design
     */
    public function __construct(CustomTranslationRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function finds data based on a request and returns it with optional pagination and
     * filtering.
     * 
     * param Request request  is an instance of the Request class in Laravel, which contains
     * all the data that was sent with the HTTP request. It can be used to retrieve input data,
     * headers, cookies, and other information related to the request.
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated using the  parameter.
     * param perPage The number of results to be displayed per page in case of pagination.
     * param get  is a string parameter that specifies the columns to retrieve from the database.
     * It is used to limit the amount of data retrieved from the database and improve performance. If
     *  is not specified, all columns will be retrieved.
     * 
     * return The `findBy` method is being called on the repository object with the parameters passed
     * in, and the result of that method call is being returned.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10, $get = '')
    {
        return $this->repo->findBy($request, $pagination, $perPage, $get);
    }

    /**
     * This function saves data from a request using a repository and returns the saved data.
     * 
     * param Request request  is an instance of the Request class in Laravel, which contains
     * all the data that was sent in the HTTP request. It can be used to retrieve input data, files,
     * headers, cookies, and more. In this case, it is being passed to the store method as an argument
     * so that the
     * 
     * return The data returned by the `save()` method of the repository class is being returned.
     */
    public function store(Request $request)
    {
        $data = $this->repo->save($request);
        return $data;
    }

    /**
     * This PHP function updates data using a repository and returns a custom resource.
     * 
     * param Request request  is an instance of the Request class, which contains the data
     * sent by the client in the HTTP request. It includes information such as the HTTP method,
     * headers, and any data sent in the request body. In this case, it is being used to pass data to
     * the save method of the repository
     * param id  is a parameter that represents the unique identifier of the resource being
     * updated. It is used to identify the specific resource that needs to be updated in the database.
     * 
     * return A CustomTranslationResource object is being returned.
     */
    public function update(Request $request, $id)
    {
        $data = $this->repo->save($request, $id);
        return new CustomTranslationResource($data);
    }

    /**
     * This function returns a collection of CustomTranslationListResource based on the parameters
     * passed to it.
     * 
     * param Request request  is an instance of the Request class which contains the HTTP
     * request information such as headers, parameters, and cookies. It is used to retrieve data from
     * the client-side and pass it to the server-side for processing. In this case, it is used to
     * retrieve any query parameters that may be passed in
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the  parameter. If set to
     * false, all results will be returned in a single response.
     * param perPage The number of items to be displayed per page in the paginated list. In this case,
     * it is set to 10.
     * 
     * return a collection of CustomTranslationListResource objects obtained from the list method of
     * the repo object, which takes in a Request object, a boolean value for pagination, and an integer
     * value for the number of items per page. The collection is then accessed and returned.
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        return CustomTranslationListResource::collection($this->repo->list($request, $pagination, $perPage))->collection;
    }
}
