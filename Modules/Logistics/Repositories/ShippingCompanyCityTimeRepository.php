<?php

namespace Modules\Logistics\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Basic\Repositories\BasicRepository;
use Modules\Logistics\Entities\ShippingCompanyCityTime;

class ShippingCompanyCityTimeRepository extends BasicRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id', 'status', 'shipping_company_id','city_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ShippingCompanyCityTime::class;
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
     * return the value of the property `searchRelationShip` of the object `->model`.
     */
    public function getFieldsRelationShipSearchable()
    {
        return $this->model->searchRelationShip;
    }


    /**
     * This function finds data based on the given request parameters and returns it with pagination
     * and specified number of results per page.
     *
     * param Request request  is an instance of the Request class in Laravel, which represents
     * an HTTP request. It contains information about the request such as the HTTP method, URL,
     * headers, and any data sent in the request body.
     * param pagination A boolean value that determines whether or not to paginate the results. If set
     * to true, the results will be paginated based on the  parameter. If set to false, all
     * results will be returned without pagination.
     * param perPage The number of items to be displayed per page in case of pagination.
     * param get The "get" parameter is used to specify which columns to retrieve from the database.
     * By default, all columns are retrieved. However, if you only need specific columns, you can pass
     * them as an array to the "get" parameter. For example, if you only need the "name" and
     *
     * return The `findBy` function is returning the result of calling the `all` function with the
     * parameters passed to it. The `all` function is likely a method that retrieves data from a
     * database or other data source based on the parameters passed to it. The `findBy` function is
     * simply a wrapper around the `all` function that allows for easier use with specific parameters.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 4, $orderBy = [], $moreConditionForFirstLevel = [], $recursiveRel = [], $get = '')
    {
        return $this->all($request->all(), pagination: $pagination, perPage: $perPage, orderBy: $orderBy, moreConditionForFirstLevel: $moreConditionForFirstLevel, recursiveRel: $recursiveRel, get: $get);
    }

    /**
     * This PHP function finds a record by its ID and returns it with its translations in a specific
     * language.
     *
     * param id The parameter "id" is the unique identifier of the record that we want to retrieve
     * from the database. It is used to specify the primary key value of the record we want to find.
     *
     * return The `findOne` function is returning the result of the `find` function with the ``
     * parameter passed in, along with an array of column names `['*']` and a relationship to eager
     * load `translation.language`.
     */
    public function findOne($id)
    {
        return $this->find($id, ['*']);
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
            return $data;
        });
    }

    /**
     * This PHP function lists all active items with optional pagination and sorting.
     *
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request such as the HTTP
     * method, headers, and any data sent in the request.
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the perPage parameter. If set to
     * false, all results will be returned in a single response.
     * param perPage The number of records to be displayed per page in case pagination is enabled. In
     * this case, the default value is 10.
     *
     * return The `list` function is returning the result of calling the `all` function with the
     * following parameters:
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        return $this->all(search: $request->all(), orderBy: ['column' => 'id', 'order' => 'desc'], pagination: $pagination, perPage: $perPage);
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
        return [];
    }
}
