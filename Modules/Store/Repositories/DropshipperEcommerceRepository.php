<?php

namespace Modules\Store\Repositories;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Store\Entities\DropshipperEcommerce;
use Modules\Basic\Repositories\BasicRepository;

class DropshipperEcommerceRepository extends BasicRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'phone',
        'email',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DropshipperEcommerce::class;
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
     * The function returns an empty array for a translation key in PHP.
     *
     * return An empty array is being returned.
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
     * param orderBy An array that specifies the order in which the results should be sorted. It can
     * contain one or more fields and their corresponding sort direction (ASC or DESC). For example,
     * ['name' => 'ASC', 'created_at' => 'DESC'] would sort the results by name in ascending order and
     * then
     * param moreConditionForFirstLevel An array of additional conditions to be applied to the first
     * level of the query. These conditions are added to the WHERE clause of the SQL query.
     * param recursiveRel This parameter is used to specify the relationships that should be
     * recursively loaded with the main model. It is an array that contains the names of the
     * relationships that should be loaded recursively. For example, if the main model has a
     * relationship with another model called "comments", and the "comments" model has a
     * param get The "get" parameter is used to specify which columns to retrieve from the database.
     * By default, all columns are retrieved. However, if you only need specific columns, you can pass
     * them as an array to the "get" parameter. For example, if you only need the "name" and
     * param limit The limit parameter is used to limit the number of records returned by the query.
     * It specifies the maximum number of records that should be returned by the query. If the number
     * of records returned by the query exceeds the limit, only the first n records (where n is the
     * value of the limit parameter)
     *
     * return The `findBy` function is returning the result of calling the `all` function with the
     * parameters passed to it.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 4, $orderBy = [], $moreConditionForFirstLevel = [], $recursiveRel = [], $get = '', $limit = null)
    {
        return $this->all($request->all(), pagination: $pagination, perPage: $perPage, orderBy: $orderBy, moreConditionForFirstLevel: $moreConditionForFirstLevel, recursiveRel: $recursiveRel, get: $get, limit: $limit);
    }

    /**
     * This function returns a single record from the database based on the provided ID.
     *
     * param id The parameter "id" is a unique identifier used to retrieve a specific record from a
     * database table. In this case, the "findOne" function is using the "id" parameter to find and
     * return a single record from the database table.
     *
     * return The `findOne` function is returning the result of the `find` function with the
     * parameter ``.
     */
    public function findOne($id)
    {
        return $this->find($id);
    }

    /**
     * This function saves data to the database, updates if an ID is provided, and syncs target markets
     * and media uploads.
     *
     * param Request request an instance of the Request class, which contains the data submitted in
     * the HTTP request
     * param id The  parameter is an optional parameter that represents the ID of the record being
     * updated. If it is not provided, a new record will be created.
     *
     * return either the updated data if  is set, or the newly created data if  is not set.
     */
    public function save(Request $request)
    {
        return DB::transaction(function () use ($request) {
            //todo change
            $dropecomerce  = DropshipperEcommerce::where('dropshipper_id', $request->dropshipper_id)
                ->where('store_type', $request->store_type)->first();

            if ($dropecomerce) {
                $data = $this->update($request->all(), $dropecomerce->id);
            } else {
                $data = $this->create($request->all());
            }
            return isset($dropecomerce) ?  $dropecomerce : $data;
        });
    }

    public function userNameMerchant(Request $request)
    {
        return DB::transaction(function () use ($request) {

            $dropecomerce  = DropshipperEcommerce::where('dropshipper_id', $request->dropshipper_id)
                ->where('store_type', $request->store_type)->where('username', $request->username)->first();


            return isset($dropecomerce) ?  $dropecomerce : null;
        });
    }

    public function userNameMerchantEasyMode(Request $request)
    {
        return DB::transaction(function () use ($request) {

            $dropecomerce  = DropshipperEcommerce::where('owner_id', $request->owner_id)->where('store_id', $request->store_id)
                ->where('store_type', $request->store_type)->first();

            return isset($dropecomerce) ?  $dropecomerce : null;
        });
    }
}
