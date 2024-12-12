<?php

namespace Modules\Order\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Basic\Repositories\BasicRepository;
use Modules\Order\Entities\PendingOrder;

class PendingOrderRepository extends BasicRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'district',
        'customer_city',
        'customer_country',
        'source_platform',
        'payment_method',
        'duplicated_order_ids',
        'is_duplicated',
        'invalid',
        'message',
        'dropshipper_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PendingOrder::class;
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
     * It takes a request object, and returns a collection of models
     *
     * param Request request The request object
     * param pagination true/false
     * param perPage The number of items to return per page.
     *
     * return The return value is the result of the all() method.
     */
    public function findBy(Request $request, $moreConditionForFirstLevel, $orderBy, $pagination, $perPage, $get, $withRelations, $latest, $limit = null, $recursiveRel = [])
    {

        return $this->all($request->all(), moreConditionForFirstLevel: $moreConditionForFirstLevel, pagination: $pagination, perPage: $perPage, get: $get, withRelations: $withRelations, latest: $latest, limit: $limit, recursiveRel: $recursiveRel);
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
               $data=  $this->update($request->all(), $id);
            } else {
                $data=   $this->create($request->all());
            }
            return $this->find($data->id);
        });
    }

    /**
     * It returns a list of all the active users.
     *
     * param Request request The request object
     * param pagination true/false
     * param perPage The number of items to show per page.
     * return A collection of all the active users.
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        return $this->all(search: $request->all(), orderBy: ['column' => 'id', 'order' => 'asc'], pagination: $pagination, perPage: $perPage);
    }
}
