<?php

namespace Modules\Order\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Basic\Repositories\BasicRepository;
use Modules\Order\Entities\AttemptsLog;

class AttemptsLogRepository extends BasicRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'order_id',
        'status_id',
        'sub_status_id',
        'remark_id',
        'attempts_count',
        'validated_at',
        'first_validation',
        'last_edit_order',
        'notes',
        'created_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AttemptsLog::class;
    }

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * It returns an array of the fields that are searchable in the relationship
     *
     * @return The searchRelationShip array.
     */
    public function getFieldsRelationShipSearchable()
    {
        return $this->model->searchRelationShip;
    }

    /**
     * It returns the translation key of the model
     *
     * @return The translation key of the model.
     */
    public function translationKey()
    {
        return $this->model->translationKey();
    }

     /**
     * This function finds records based on specified parameters and returns them with optional
     * pagination and ordering.
     *
     * param Request request  is an instance of the Request class in Laravel, which represents
     * an HTTP request. It contains information about the request such as the HTTP method, URL,
     * headers, and any data sent in the request body.
     * param pagination a boolean value indicating whether to enable pagination or not. If set to
     * true, the result will be paginated based on the perPage parameter.
     * param perPage The number of records to be displayed per page in case of pagination.
     * param orderBy An array that specifies the order in which the results should be sorted. It
     * should be in the format of ['column_name' => 'asc/desc']. If multiple columns are specified, the
     * sorting will be done in the order they are listed in the array.
     * param moreConditionForFirstLevel moreConditionForFirstLevel is an optional parameter that
     * allows you to add additional conditions to the query for the first level of the model. These
     * conditions are added to the WHERE clause of the SQL query. This parameter is useful when you
     * need to filter the results based on some additional criteria that are not included
     * param recursiveRel The recursiveRel parameter is an array that specifies the relationships that
     * should be recursively loaded when retrieving the data. This is useful when you need to retrieve
     * data from related tables that are several levels deep. For example, if you have a User model
     * that has a relationship with a Post model, which in turn
     * param get The "get" parameter is used to specify which columns to retrieve from the database.
     * By default, all columns are retrieved. However, if you only need specific columns, you can pass
     * them as an array to the "get" parameter. For example, if you only need the "name" and
     *
     * return The `findBy` function is returning the result of calling the `all` function with the
     * parameters passed to `findBy`.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 4, $orderBy = [],
        $moreConditionForFirstLevel = [], $recursiveRel = [], $get = '',$withRelations=[])
    {
        $orderBy = $orderBy ?? $request->orderBy ?? ['column' => 'id', 'order' => 'desc'];
        return $this->all($request->all(), pagination: $pagination, perPage: $perPage, orderBy: $orderBy,
            moreConditionForFirstLevel: $moreConditionForFirstLevel, recursiveRel: $recursiveRel, get: $get,withRelations:$withRelations);
    }

    /**
     * It returns a single record from the database, with all columns, and with the language column
     * from the translation table
     *
     * param id The id of the record you want to find
     *
     * @return The findOne method is returning the result of the find method.
     */
    public function findOne($id)
    {
        return $this->find($id, ['*']);
    }

    /**
     * It saves the data to the database and uploads the image to the server
     *
     * param Request request The request object
     * param id The id of the record to be updated.
     *
     * @return The return value of the transaction closure.
     */
    public function save($request, $id = null)
    {
        return DB::transaction(function () use ($request, $id) {
            if ($id) {
                $data = $this->update($request->all(), $id);
            } else {
                $data =  $this->create($request->all());
            }
            return $data;
        });
    }

    /**
     * It returns a list of all the active users.
     *
     * param Request request The request object
     * param pagination true/false
     * param perPage The number of items to show per page.
     *
     * @return A collection of all the active users.
     */
    public function list(Request $request, $pagination = false, $perPage = 10, $moreConditionForFirstLevel = [])
    {
        return $this->all(search: $request->all(), orderBy: ['column' => 'id', 'order' => 'desc'], pagination: $pagination, perPage: $perPage, moreConditionForFirstLevel: $moreConditionForFirstLevel);
    }
}
