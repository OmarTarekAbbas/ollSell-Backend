<?php

namespace Modules\Order\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Basic\Repositories\BasicRepository;
use Modules\Order\Entities\Invoice;
use Modules\Order\Entities\Order;

class InvoiceRepository extends BasicRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'order_id',
        'dropshipper_id',
        'dropshipper_branch_id',
        'invoice_number',
        'paymentMethod',
        'costPrice',
        'subTotal',
        'grandTotal',
        'totalVat',
        'net_profit',
        'pdf_link',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Invoice::class;
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
     * It returns an array of the fields that are searchable in the relationship
     *
     * return The searchRelationShip array.
     */
    public function getFieldsRelationShipSearchable()
    {
        return $this->model->searchRelationShip;
    }

    /**
     * It returns the translation key of the model
     *
     * return The translation key of the model.
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
     * It returns a single record from the database, with all columns, and with the language column
     * from the translation table
     *
     * param id The id of the record you want to find
     *
     * return The findOne method is returning the result of the find method.
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
     * return The return value of the transaction closure.
     */
    public function save($request, $id = null)
    {
        return DB::transaction(function () use ($request, $id) {
            if ($id) {
                return $this->update($request->all(), $id);
            } else {
                return  $this->create($request->all());
            }
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
    public function list(Request $request, $pagination = false, $perPage = 10, $moreConditionForFirstLevel = [], $withRelations = [], $recursiveRel = [])
    {
        return $this->all(search: $request->all(), orderBy: ['column' => 'id', 'order' => 'desc'], pagination: $pagination, perPage: $perPage, moreConditionForFirstLevel: $moreConditionForFirstLevel, withRelations: $withRelations, recursiveRel: $recursiveRel);
    }
}
