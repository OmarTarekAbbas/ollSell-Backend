<?php

namespace Modules\CoreData\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Basic\Repositories\BasicRepository;
use Modules\CoreData\Entities\Status;

class StatusRepository extends BasicRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Status::class;
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
    public function findBy(Request $request, $moreConditionForFirstLevel = [], $orderBy = [], $pagination = false, $perPage = 10, $get = '', $withRelations = [])
    {
        return $this->all($request->all(), moreConditionForFirstLevel: $moreConditionForFirstLevel, orderBy: $orderBy, pagination: $pagination, perPage: $perPage, get: $get, withRelations: $withRelations);
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
                $data =  $this->create($request->all());
            }
            $this->updateOrCreateLanguage($data, $request, $this->translationKey());
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
     * return A collection of all the active users.
     */
    public function list(Request $request, $pagination = false, $perPage = 10, $moreConditionForFirstLevel = [])
    {
        return $this->all(search: $request->all(), orderBy: ['column' => 'id', 'order' => 'desc'], pagination: $pagination, perPage: $perPage, moreConditionForFirstLevel: $moreConditionForFirstLevel);
    }
}
