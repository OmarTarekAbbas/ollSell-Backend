<?php

namespace Modules\CoreData\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use App\Providers\LanguageTranslationEvent;
use Modules\CoreData\Repositories\LanguageRepository;
use Modules\CoreData\Http\Resources\Language\LanguageListResource;

class LanguageService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(LanguageRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function returns a collection of language list resources with optional pagination and a
     * default of 10 items per page.
     * 
     * param Request request  is an instance of the Request class which contains the HTTP
     * request information such as headers, parameters, and cookies. 
     * 
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the  parameter. 
     * 
     * param perPage The number of items to be displayed per page in the paginated list. If pagination
     * is not enabled, this parameter will not have any effect.
     * 
     * return A collection of LanguageListResource objects returned by calling the `list` method of
     * the repository with the provided parameters.
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        return LanguageListResource::collection($this->repo->list($request, $pagination, $perPage));
    }

    /**
     * This function finds data based on a request and returns it with optional pagination and a
     * specified number of results per page.
     * 
     * param Request request  is an instance of the Request class in Laravel. It contains all
     * the data that was sent with the HTTP request.
     * 
     * param pagination A boolean value that determines whether or not to paginate the results. If set
     * to true, the results will be paginated using Laravel's built-in pagination functionality.
     * 
     * param perPage The number of results to be displayed per page in case pagination is enabled.
     * 
     * return The `findBy` method is being called on a repository object with three parameters:
     * ``, ``, and ``.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10)
    {
        return $this->repo->findBy($request, $pagination, $perPage);
    }

    /**
     * This function saves data from a request and triggers a language translation event.
     * 
     * param Request request  is an instance of the Request class which contains the data
     * submitted through an HTTP request. 
     * 
     * return a boolean value. If the data is successfully saved, it will return true, otherwise it
     * will return false.
     */
    public function store(Request $request)
    {
        $data = $this->repo->save($request);
        event(new LanguageTranslationEvent($data->id));
        if ($data) {
            return true;
        }
        return false;
    }
}
