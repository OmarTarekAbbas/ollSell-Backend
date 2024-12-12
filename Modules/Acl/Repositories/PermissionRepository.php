<?php

namespace Modules\Acl\Repositories;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Acl\Entities\Permission;
use Modules\Basic\Repositories\BasicRepository;

class PermissionRepository extends BasicRepository
{
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'id', 'name', 'label'
    ];

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
     * Configure the Model
     **/
    public function model(): string
    {
        return Permission::class;
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
     * This function returns all records based on the given request parameters.
     * 
     * param Request request  is an instance of the Request class, which is used to retrieve
     * data from the HTTP request. It contains information about the current request, such as the HTTP
     * method, headers, and parameters. In this case, the  parameter is being used to retrieve
     * all the data from the HTTP request and
     * 
     * return The `findBy` function is returning the result of calling the `all` function with the
     * `->all()` parameter. The `all` function is likely a method of the class that this
     * function is a part of, and it is returning some data based on the input parameters. Without more
     * context, it is difficult to determine exactly what data is being returned.
     */
    public function findBy(Request $request)
    {
        return $this->all($request->all());
    }

    /**
     * This function returns all records from the model.
     * 
     * return The `findAll()` function is returning all the records from the database table associated
     * with the model. It is using the `get()` method of the model to retrieve the records.
     */
    public function findAll()
    {
        return $this->model->get();
    }

    /**
     * This function finds and returns a single record from a database table based on its ID.
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
     * This function saves data to the database and syncs permissions.
     * 
     * param Request request  is an instance of the Request class which contains the data sent
     * by the client in the HTTP request. It can contain data from the request body, query parameters,
     * headers, etc.
     * param id  is an optional parameter that represents the ID of the data being updated. If it
     * is provided, the function will update the existing data with the given ID. If it is not
     * provided, the function will create a new data entry.
     * 
     * return the result of a database transaction that either updates or creates data based on the
     * presence of an ID parameter in the request. It also syncs the permissions associated with the
     * data. The specific data being returned depends on whether it was updated or created.
     */
    public function save(Request $request, $id = null)
    {
        return DB::transaction(function () use ($request, $id) {
            if ($id) {
                $data = $this->update($request->all(), $id);
            } else {
                $data = $this->create($request->all());
            }
            $data->permissions()->sync((array)$request->permissions);
            return $data;
        });
    }
}
