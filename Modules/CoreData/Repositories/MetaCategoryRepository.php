<?php

namespace Modules\CoreData\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Basic\Repositories\BasicRepository;
use Modules\CoreData\Entities\MetaCategory;

class MetaCategoryRepository extends BasicRepository
{
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'id', 'name', 'category_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MetaCategory::class;
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
     * return the value of the property `searchRelationShip` of the `model` object.
     */
    public function getFieldsRelationShipSearchable()
    {
        return $this->model->searchRelationShip;
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
     * This function saves data to the database and updates or creates translations for the data.
     * 
     * param Request request  is an instance of the Request class, which contains the data
     * sent by the client in the HTTP request. It can contain data from the query string, request body,
     * headers, cookies, and more. In this function,  is used to retrieve the data sent by the
     * client and pass it
     * param id  is an optional parameter that represents the ID of the record being updated. If it
     * is provided, the function will update the existing record with the given ID. If it is not
     * provided, the function will create a new record.
     * 
     * return the result of a database transaction that either updates or creates data based on the
     * presence of an ID parameter in the request. It also updates or creates translations for the
     * data. The final result returned is the data that was updated or created.
     */
    public function save(Request $request, $id = null)
    {
        return DB::transaction(function () use ($request, $id) {
            if ($id) {
                $data = $this->update($request->all(), $id);
            } else {
                $data = $this->create($request->all());
            }
            $this->updateOrCreateLanguage($data, $request, $this->translationKey());
            return $data;
        });
    }
}
