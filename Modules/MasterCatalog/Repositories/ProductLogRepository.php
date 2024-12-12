<?php

namespace Modules\MasterCatalog\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Basic\Repositories\BasicRepository;
use Modules\MasterCatalog\Entities\ProductLog;

class ProductLogRepository extends BasicRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'quantity', 'type','product_id','user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductLog::class;
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
        return $this->find($id, ['*']);
    }

    /**
     * This function saves data to the database, updates or creates new data, syncs target markets,
     * updates or creates language translations, and uploads media files.
     *
     * param Request request an instance of the Request class, which contains the data submitted in
     * the HTTP request
     * param id The  parameter is an optional parameter that represents the ID of the record being
     * updated. If it is not provided, it means that a new record is being created.
     *
     * @return the result of the transaction, which is either the updated data if  is provided, or
     * the newly created data if  is null.
     */
    public function save(Request $request, $id = null)
    {
        return DB::transaction(function() use ($request, $id)
        {
            if($id)
            {
                $data = $this->update($request->all(), $id);
            }else
            {
                $data = $this->create($request->all());
            }
            return $data;
        });
    }

    /**
     * It returns a list of all the active records in the database
     *
     * param Request request The request object
     * param pagination true/false
     * param perPage The number of items to be displayed per page.
     * param moreConditionForFirstLevel This is an array of conditions that you want to add to the
     * first level of the query.
     */
    public function list(Request $request, $pagination = true, $perPage = 20, $moreConditionForFirstLevel = [],
        $recursiveRel = [],$withRelations=[])
    {//todo change
        $request->merge(['status' => activeType()['as']]);
        $orderby = $request->orderBy ?? ['column' => 'id', 'order' => 'desc'];
        return $this->all(search: $request->all(), orderBy: $orderby, pagination: $pagination, perPage: $perPage,
            moreConditionForFirstLevel: $moreConditionForFirstLevel, recursiveRel: $recursiveRel,withRelations:$withRelations);
    }

}
