<?php

namespace Modules\CoreData\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Basic\Repositories\BasicRepository;
use Modules\CoreData\Entities\Notification;

class NotificationRepository extends BasicRepository
{
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'id', 'title',
        'content',
        'user_id',
        'user_type',
        'seen',
        'seenAt',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Notification::class;
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
     * Return searchable fields
     *
     * return array
     */
    public function getFieldsSearchable(): array
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
     * This function finds and returns data based on specified parameters and pagination options.
     * 
     * param Request request  is an instance of the Request class in Laravel, which contains
     * the HTTP request information such as the request method, headers, and input data.
     * param pagination A boolean value that determines whether or not to paginate the results. If set
     * to true, the results will be paginated based on the  parameter. If set to false, all
     * results will be returned.
     * param perPage The number of records to be displayed per page in case of pagination.
     * param pluck An array of columns to retrieve from the database. If provided, only the specified
     * columns will be returned in the result set. For example, if  = ['id', 'name'], only the
     * 'id' and 'name' columns will be retrieved from the database.
     * param get The "get" parameter is used to specify the columns that should be retrieved from the
     * database. It can be an array of column names or a string of comma-separated column names. If
     * left empty or set to "*", all columns will be retrieved.
     * param moreConditionForFirstLevel moreConditionForFirstLevel is an array of additional
     * conditions that are applied to the first level of the query. These conditions are added to the
     * WHERE clause of the SQL query. The purpose of this parameter is to allow for more specific
     * filtering of the results returned by the query.
     * param recursiveRel An array of relationships to be recursively loaded. For example, if a model
     * has a relationship with another model, and that model has a relationship with yet another model,
     * you can use recursiveRel to load all of those relationships in a single query.
     * param withRelations An array of relationships to eager load with the query. This can help
     * reduce the number of database queries needed to retrieve related data.
     * param latest The "latest" parameter is a boolean value that determines whether the results
     * should be ordered by the latest created_at timestamp or not. If it is set to true, the results
     * will be ordered by the latest created_at timestamp. If it is set to false or not provided, the
     * results will be ordered
     * param limit The limit parameter is not used in this function. It is not passed as an argument
     * to any of the underlying functions called by this function. Therefore, it is not relevant to the
     * behavior of this function.
     * 
     * return The function `findBy` is returning the result of calling the `all` method with several
     * parameters passed to it. The specific result depends on the implementation of the `all` method.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10, $pluck = [], $get = '', $moreConditionForFirstLevel = [], $recursiveRel = [], $withRelations = [], $latest, $limit = 0)
    {
        return $this->all($request->all(), ['*'], $withRelations, $recursiveRel, $moreConditionForFirstLevel, $pluck, [], $get, null, null, $pagination, $perPage, latest: $latest);
    }

    /**
     * This function finds and returns a single record from a database table based on the provided ID.
     * 
     * param id  is a parameter that represents the unique identifier of the record that you want
     * to retrieve from the database. It is used to query the database and fetch a single record that
     * matches the specified id.
     * 
     * return The `findOne` function is returning the result of calling the `find` function with the
     * `` parameter and an array containing a single element `'*'`. The `find` function is likely
     * returning a single record from a database table based on the `` parameter and the array of
     * columns to select. The specific implementation of the `find` function is not shown in the code
     * snippet provided
     */
    public function findOne($id)
    {
        return $this->find($id, ['*']);
    }

    /**
     * This function saves data to the database and updates or creates language translations.
     * 
     * param Request request an instance of the Request class, which contains the HTTP request data
     * param id The  parameter is an optional parameter that represents the ID of the record being
     * updated. If it is provided, the function will update the existing record with the given ID,
     * otherwise it will create a new record.
     * 
     * return the result of the transaction, which could be the data object if it was created or
     * updated successfully.
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
     * This function lists items with optional pagination and additional conditions and relationships.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * contains the HTTP request information such as the request method, headers, and parameters. It is
     * used to retrieve the input data from the user and pass it to the function.
     * param moreConditionForFirstLevel An array of additional conditions to be applied to the first
     * level of the query. These conditions will be added to the existing conditions specified in the
     * ->all() parameter.
     * param recursiveRel An array of relationships to be recursively loaded with the main model. For
     * example, if the main model has a relationship with another model called "comments", and the
     * "comments" model has a relationship with another model called "user", then you can pass
     * ['comments.user'] as the value of 
     * param pagination A boolean value that determines whether or not to enable pagination for the
     * query results. If set to true, the results will be paginated based on the  parameter. If
     * set to false, all results will be returned without pagination.
     * param perPage The number of records to be displayed per page in case pagination is enabled.
     * 
     * return The `list` function is returning the result of the `all` function with the following
     * parameters:
     */
    public function list(Request $request, $moreConditionForFirstLevel = [], $recursiveRel = [], $pagination = false, $perPage = 10)
    {
        return $this->all($request->all(), ['*'], [], $recursiveRel, $moreConditionForFirstLevel, [], ['column' => 'id', 'order' => 'desc'], '', null, null, $pagination, $perPage);
    }
}
