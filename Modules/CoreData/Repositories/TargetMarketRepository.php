<?php

namespace Modules\CoreData\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Basic\Repositories\BasicRepository;
use Modules\CoreData\Entities\TargetMarket;

class TargetMarketRepository extends BasicRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id', 'status', 'code'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TargetMarket::class;
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
     * This function saves data to the database and updates or creates language translations.
     * 
     * param Request request  is an instance of the Request class, which contains the data
     * sent by the client in the HTTP request. It can contain data from the query string, request body,
     * headers, cookies, and more. In this function,  is used to retrieve the data sent by the
     * client and pass it
     * param id  is an optional parameter that represents the ID of the record being updated. If it
     * is provided, the function will update the existing record with the given ID. If it is not
     * provided, the function will create a new record.
     * 
     * return the result of a database transaction that either updates or creates data based on the
     * presence of an ID parameter in the request. It also updates or creates translations for the
     * data. The final result returned is the data that was updated or created.
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
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request such as the HTTP
     * method, headers, and any data sent in the request.
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the perPage parameter. If set to
     * false, all results will be returned in a single response.
     * param perPage The number of items to be displayed per page in case pagination is enabled. In
     * this case, it is set to 10.
     * 
     * return The `list` function is returning the result of calling the `all` function with the
     * following parameters:
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        //todo change
        $request->merge(['status' => activeType()['as']]);
        return $this->all(search: $request->all(), orderBy: ['column' => 'order', 'order' => 'desc'], pagination: $pagination, perPage: $perPage);
    }
}
