<?php

namespace Modules\Order\Repositories;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\DB;
use Modules\Order\Enums\OrderEnum;
use Modules\Basic\Repositories\BasicRepository;
use Modules\Order\Actions\Order\StartValidationFlowAction;

class OrderRepository extends BasicRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'paymentMethod',
        'shippingMethod',
        'totalQuantity',
        'shippingFees',
        'easy_order_id',
        'dropshipper_branch_id',
        'remark_id',
        'unitPrice',
        'totalPrice',
        'subTotal',
        'dropshipper_id',
        'status_id',
        'created_at',
        'customerName',
        'customerPhone',
        'customerAddress',
        'customerLocation',
        'country_id',
        'customerCity',
        'id',
        'district',
        'net_profit',
        'is_import',
        'source_platform',
        'created_platform',
        'validation_operator_id',
        'ollops_confirmation_status',
        'validated_by',
        'validated'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Order::class;
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
                $data = $this->update($request->all(), $id);
            } else {
                // generate token with 10 characters
                $token = $this->generateToken(10);

                $request->merge(['token' => $token]);
                $data = $this->create($request->all());

                if (setting('validation_type') == 'automatic' && $data->paymentMethod == 2) {
                    // update order to be in pending state
                    $data->update([
                        'status_id' => OrderEnum::PENDING_STATUS,
                    ]);

                    // start validation process
                    // Prepare data for validation flow
                    $orderIds = [$data->id]; // Assuming you want to validate this order only
                    $validationRequest = new Request(['orderIds' => $orderIds]);
                    // Start validation process
                    App(StartValidationFlowAction::class, ['request' => $validationRequest])->execute();
                }
            }
            return $this->find($data->id);
        });
    }

        /**
     * It generates a random token with the specified length
     *
     * param length The length of the token
     *
     * return The generated token
     */
    public function generateToken($length)
    {
        $token = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);

        if ($this->model->where('token', $token)->exists()) {
            return $this->generateToken($length);
        }

        return $token;
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
    public function list(Request $request, $pagination = false, $perPage = 10, $moreConditionForFirstLevel = [], $withRelations = [], $recursiveRel = [], $orderBy = null)
    {
        return $this->all(search: $request->all(), orderBy: $orderBy ?? ['column' => 'id', 'order' => 'desc'], pagination: $pagination, perPage: $perPage, moreConditionForFirstLevel: $moreConditionForFirstLevel, withRelations: $withRelations, recursiveRel: $recursiveRel);
    }

    public function exportList(Request $request, $pagination = true, $perPage = 10, $moreConditionForFirstLevel = [], $withRelations = [], $recursiveRel = [], $orderBy = null)
    {
        return $this->all(search: $request->all(), orderBy: $orderBy ?? ['column' => 'id', 'order' => 'desc'], pagination: $pagination, perPage: $perPage, moreConditionForFirstLevel: $moreConditionForFirstLevel, withRelations: $withRelations, recursiveRel: $recursiveRel, isDatatable: true);
    }
}
