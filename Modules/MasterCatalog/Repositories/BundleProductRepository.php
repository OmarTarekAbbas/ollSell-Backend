<?php

namespace Modules\MasterCatalog\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Basic\Repositories\BasicRepository;
use Modules\MasterCatalog\Entities\BundleProduct;

class BundleProductRepository extends BasicRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
       'product_id', 'bundle_id', 'count'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return BundleProduct::class;
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
     * It takes a request object, and returns a collection of models
     * 
     * param Request request The request object
     * param pagination true/false
     * param perPage The number of items to return per page.
     * 
     * @return The return value is the result of the all() method.
     */
    public function findBy(Request $request, $orderBy = [], $moreConditionForFirstLevel = [], $limit = null, $pagination = false, $perPage = 10)
    {
        return $this->all($request->all(), orderBy: $orderBy, moreConditionForFirstLevel: $moreConditionForFirstLevel, limit: $limit, pagination: $pagination, perPage: $perPage);
    }

    /**
     * > This function returns the result of the find function, which is a single record  
     * 
     * param id The id of the record you want to find.
     * 
     * @return The findOne method is returning the find method.   
     */
    public function findOne($id)
    {
        return $this->find($id, ['*'], []);
    }

    /**
     * If the user has a record in the database, update it, otherwise create a new record.
     * 
     * param Request request The request object
     * param id The id of the model you want to update.
     * 
     * @return The data is being returned.
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
     * It returns a list of all the active users.
     * 
     * param Request request The request object
     * param pagination true/false
     * param perPage The number of items to show per page.
     * 
     * @return A collection of all the active users.
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        $request->merge(['status' => activeType()['as']]);
        return $this->all(search: $request->all(), orderBy: ['column' => 'id', 'order' => 'desc'], pagination: $pagination, perPage: $perPage);
    }
}
