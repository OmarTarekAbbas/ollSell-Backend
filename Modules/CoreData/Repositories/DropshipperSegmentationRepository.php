<?php

namespace Modules\CoreData\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Basic\Repositories\BasicRepository;
use Modules\CoreData\Entities\DropshipperSegmentation;

class DropshipperSegmentationRepository extends BasicRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id', 'from', 'to'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DropshipperSegmentation::class;
    }
    /**
     * Return searchable fields
     *
     * return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * This function returns the searchable relationship fields of a model.
     * 
     * return the value of the property `searchRelationShip` of the `model` object.
     */
    public function getFieldsRelationShipSearchable()
    {
        return $this->model->searchRelationShip;
    }

    /**
     * This function returns the translation key of a model.
     * 
     * return The function `translationKey()` is being called on the `` property of the current
     * object, and the result of that function call is being returned. The exact value being returned
     * depends on the implementation of the `translationKey()` function in the model class.
     */
    public function translationKey()
    {
        return $this->model->translationKey();
    }

    /**
     * This function finds data based on the given request parameters and returns it with optional
     * pagination and selection of specific fields.
     * 
     * param Request request  is an instance of the Request class in Laravel, which represents
     * an HTTP request. It contains information about the request such as the HTTP method, headers, and
     * parameters.
     * param pagination a boolean value that determines whether or not to paginate the results of the
     * query. If set to true, the results will be paginated based on the perPage parameter.
     * param perPage The number of items to be displayed per page when using pagination.
     * param get The "get" parameter is used to specify which columns to retrieve from the database.
     * By default, all columns are retrieved. If you only need specific columns, you can pass them as
     * an array to the "get" parameter. For example, if you only need the "name" and "email
     * 
     * return The `findBy` function is returning the result of calling the `all` function with the
     * parameters passed to it, including the `->all()` array, a boolean value for
     * ``, an integer value for ``, and a string value for ``.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 4, $orderBy = [], $moreConditionForFirstLevel = [], $recursiveRel = [], $get = '')
    {
        return $this->all($request->all(), pagination: $pagination, perPage: $perPage, orderBy: $orderBy, moreConditionForFirstLevel: $moreConditionForFirstLevel, recursiveRel: $recursiveRel, get: $get);
    }

    /**
     * This PHP function finds a record by its ID and returns it with its translation in a specific
     * language.
     * 
     * param id  is the identifier of the record that you want to retrieve from the database. It is
     * used to specify the primary key value of the record you want to find.
     * 
     * return The `findOne` function is returning the result of the `find` function with the specified
     * `` parameter, along with the columns specified in the second parameter `['*']` and the
     * related translation language data specified in the third parameter `['translation.language']`.
     */
    public function findOne($id)
    {
        return $this->find($id, ['*'], ['translation.language']);
    }

    /**
     * This function saves data to the database, updates or creates new data, deletes media if
     * requested, and uploads new media.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * contains the HTTP request information sent by the client to the server.
     * param id The  parameter is an optional parameter that represents the ID of the record being
     * updated. If it is not provided, it means that a new record is being created.
     * 
     * return the result of the transaction, which includes the creation or update of data, deletion
     * of media if requested, and uploading of media. The final result returned is the data object.
     */
    public function save(Request $request, $id = null)
    {
        return DB::transaction(function () use ($request, $id) {
            if ($id) {
                $data = $this->update($request->all(), $id);
            } else {
                $data = $this->create($request->all());
            }
            $this->updateOrCreateLanguage($data, $request, $this->translationKey());
            return $data;
        });
    }

    /**
     * This PHP function lists items with a specified status and orders them by ascending order.
     * 
     * param Request request  is an instance of the Request class in Laravel, which represents
     * an HTTP request. It contains information about the request such as the HTTP method, URL,
     * headers, and any data sent in the request body.
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the perPage parameter. If set to
     * false, all results will be returned.
     * param perPage The number of records to be displayed per page in case pagination is enabled. In
     * this case, it is set to 10.
     * param recursiveRel The recursiveRel parameter is an optional array that specifies the related
     * models to be eager loaded recursively. This means that not only the immediate related models
     * will be loaded, but also their related models and so on. This is useful when you need to access
     * nested relationships in your code without having to make additional queries
     * 
     * return the result of calling the `all()` method on `` object with the following
     * parameters:
     */
    public function list(Request $request, $pagination = false, $perPage = 10, $recursiveRel = [])
    {
        //todo change
        $request->merge(['status' => activeType()['as']]);
        return $this->all(search: $request->all(), orderBy: ['column' => 'order', 'order' => 'desc'], pagination: $pagination, perPage: $perPage, recursiveRel: $recursiveRel);
    }
}
