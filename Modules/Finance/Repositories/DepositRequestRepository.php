<?php

namespace Modules\Finance\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Finance\Entities\DepositRequest;
use Modules\Basic\Repositories\BasicRepository;

class DepositRequestRepository extends BasicRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id', 'dropshipper_id', 'status', 'amount', 'reason'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DepositRequest::class;
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
     * This PHP function finds data based on a request and returns it with optional pagination and
     * filtering.
     *
     * param Request request  is an instance of the Request class in Laravel, which represents
     * an HTTP request. It contains information about the request such as the HTTP method, headers, and
     * parameters.
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the perPage parameter.
     * param perPage The number of items to be displayed per page when pagination is enabled.
     * param get  is a parameter that allows the user to specify which columns they want to
     * retrieve from the database. It is an optional parameter and if not provided, the function will
     * retrieve all columns.
     *
     * return The `findBy` function is returning the result of calling the `all` function with the
     * parameters passed to it, including the `->all()` array, ``, ``, and
     * ``.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10, $get = "")
    {
        return $this->all($request->all(), get: $get, pagination: $pagination, perPage: $perPage);
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
     * param Request request  is an instance of the Request class, which contains the data
     * submitted in an HTTP request. It is used to retrieve input data, such as form data or query
     * parameters, from the request. In this case, it is used to retrieve data to be saved or updated in
     * the database.
     * param id  is an optional parameter that represents the ID of the record being updated. If
     * is provided, the function will update the existing record with the given ID. If  is not
     * provided, the function will create a new record.
     *
     * return the result of a database transaction. If the transaction is successful, it will return
     * the result of either the `update` or `create` method depending on whether an `` parameter was
     * provided. If the transaction fails, an exception will be thrown.
     */
    public function save(Request $request, $id = null)
    {
        return DB::transaction(function () use ($request, $id) {
            if ($id) {
                $data =  $this->update($request->all(), $id);
                if ($request->avatar) {
                    $data->media()->delete();
                }
            } else {
                $data = $this->create($request->all());
            }
            $this->media_upload($data, $request, createFileNameServer($this->model(), $data->id), pathType()['ip'], mediaType()['am']);
            return $this->find($data->id);
        });
    }

    /**
     * It returns a list of all the active users.
     *
     * param Request request The request object
     * param pagination true/false
     * param perPage The number of items to show per page.
     *
     * return A collection of all the active users.
     */
    public function list(Request $request, $pagination = false, $perPage = 10, $moreConditionForFirstLevel = [])
    {
        return $this->all(search: $request->all(), orderBy: ['column' => 'id', 'order' => 'desc'], pagination: $pagination, perPage: $perPage, moreConditionForFirstLevel: $moreConditionForFirstLevel);
    }
}
