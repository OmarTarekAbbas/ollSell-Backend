<?php

namespace Modules\CoreData\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Basic\Repositories\BasicRepository;
use Modules\CoreData\Entities\Language;

class LanguageRepository extends BasicRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code', 'id', 'status'
    ];
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Language::class;
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
     * The function returns an empty array for a translation key in PHP.
     * 
     * return An empty array is being returned.
     */
    public function translationKey()
    {
        return [];
    }

    /**
     * This function finds records based on the given request parameters and returns them with optional
     * pagination and a specified number of records per page.
     * 
     * param Request request  is an instance of the Request class in Laravel, which contains
     * all the data that was sent with the HTTP request. It can be used to retrieve input data,
     * headers, cookies, and other information related to the request.
     * param pagination A boolean value that determines whether or not to paginate the results. If set
     * to true, the results will be paginated based on the  parameter. If set to false, all
     * results will be returned without pagination.
     * param perPage The number of items to be displayed per page in case pagination is enabled.
     * 
     * return The `findBy` function is returning the result of calling the `all` function with the
     * `->all()` array as the first argument, and the values of `` and `` as
     * the named parameters `pagination` and `perPage`, respectively. The specific return value depends
     * on the implementation of the `all` function.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10)
    {
        return $this->all($request->all(), pagination: $pagination, perPage: $perPage);
    }

    /**
     * This function finds and returns a single record from the database based on the provided ID.
     * 
     * param id The parameter "id" is the unique identifier of the record that we want to retrieve
     * from the database. The "findOne" function is a method of a class that extends a database
     * abstraction layer, and it uses the "find" method to retrieve the record with the specified "id"
     * from the database
     * 
     * return The `findOne` function is returning the result of the `find` function with the parameter
     * ``.
     */
    public function findOne($id)
    {
        return $this->find($id);
    }

    /**
     * This function saves data to the database using Laravel's DB transaction method.
     * 
     * param Request request An instance of the Request class, which contains the data submitted in
     * the HTTP request.
     * param id The "id" parameter is an optional parameter that represents the ID of the record being
     * updated. If it is provided, the function will update the existing record with the given ID. If
     * it is not provided, the function will create a new record.
     * 
     * return the result of a database transaction. If an  is provided, it updates the record with
     * the given  using the update() method, otherwise it creates a new record using the create()
     * method. The result of the transaction (either the updated or newly created record) is then
     * returned.
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
     * This PHP function lists items with a specified order and pagination.
     * 
     * param Request request  is an instance of the Request class in Laravel, which represents
     * an HTTP request. It contains information about the request such as the HTTP method, headers, and
     * any data sent in the request.
     * param pagination A boolean value that determines whether or not to paginate the results. If set
     * to true, the results will be paginated based on the  parameter. If set to false, all
     * results will be returned without pagination.
     * param perPage The number of items to be displayed per page in the pagination. In this case, it
     * is set to 10 by default but can be overridden by passing a different value as an argument.
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
