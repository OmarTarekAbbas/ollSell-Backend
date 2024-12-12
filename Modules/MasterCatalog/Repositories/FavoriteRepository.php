<?php

namespace Modules\MasterCatalog\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Basic\Repositories\BasicRepository;
use Modules\MasterCatalog\Entities\Favorite;

class FavoriteRepository extends BasicRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id', 'dropshipper_id', 'product_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Favorite::class;
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
    public function findBy(Request $request, $pagination = false, $perPage = 10, $get = '')
    {
        return $this->all($request->all(), pagination: $pagination, perPage: $perPage, get: $get);
    }

    /**
     * > This function returns the result of the find function, which is a single record  
     * 
     * param id The id of the record you want to find.
     * 
     * return The findOne method is returning the find method.
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
     * return The data is being returned.
     */
    public function save(Request $request)
    {
        return DB::transaction(function () use ($request) {
            return $this->createFavorite($request);
        });
    }

    /**
     * It takes an array of product ids and creates a new favorite for each product id
     * 
     * param request the request object
     * 
     * return  is being returned.
     */
    public function createFavorite($request)
    {//todo change
        $products = $request->products;
        foreach ($products as  $product) {
            $favorite = new $this->model();
            $favorite->dropshipper_id = user()->id;
            $favorite->product_id = $product;
            $favorite->save();
        }
        return $favorite;
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
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        return $this->all(search: $request->all(), orderBy: ['column' => 'id', 'order' => 'desc'], pagination: $pagination, perPage: $perPage);
    }
}
