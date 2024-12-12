<?php

namespace Modules\Acl\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Acl\Entities\Role;
use Modules\Basic\Repositories\BasicRepository;

class RoleRepository extends BasicRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id', 'name', 'label', 'type'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Role::class;
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
     * Return searchable fields
     *
     * return arrayModules
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
     * all the parameters from the current request and
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
     * This function finds and returns a single record from a database table based on the provided ID.
     * 
     * param id  is a parameter that represents the unique identifier of the record that needs to be
     * retrieved from the database. It is used to query the database and fetch a single record that
     * matches the specified id.
     * 
     * return The `findOne` function is returning the result of calling the `find` function with the
     * given `` and an array containing a single element `'*'`. The `find` function is likely a
     * database query function that returns a single record matching the given ``. Therefore, the
     * `findOne` function is likely returning a single record from the database with the given ``.
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

    /**
     * This function lists items with optional pagination and additional conditions and relationships.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * contains the HTTP request information such as the request method, headers, and parameters.
     * param moreConditionForFirstLevel An array of additional conditions to be applied to the first
     * level of the query. These conditions will be added to the existing conditions specified in the
     * ->all() parameter.
     * param recursiveRel An array of relationships to be recursively loaded with the main model. For
     * example, if the main model has a relationship with another model called "comments", and the
     * "comments" model has a relationship with another model called "user", then you can pass
     * ['comments.user'] as the value of 
     * param pagination A boolean value that determines whether or not to enable pagination for the
     * query results. If set to true, the results will be paginated based on the  parameter. If
     * set to false, all results will be returned without pagination.
     * param perPage The number of records to be displayed per page in case pagination is enabled.
     * 
     * return The `list` function is returning the result of the `all` function with the following
     * parameters:
     */
    public function list(Request $request, $moreConditionForFirstLevel = [], $recursiveRel = [], $pagination = false, $perPage = 10)
    {
        return $this->all($request->all(), ['*'], [], $recursiveRel, $moreConditionForFirstLevel, [], ['column' => 'id', 'order' => 'desc'], '', null, null, $pagination, $perPage);
    }

    /**
     * This function deletes a record from the database based on the given ID.
     * 
     * param id The id parameter is the unique identifier of the record that needs to be deleted from
     * the database.
     * 
     * return a boolean value. If the data is found and deleted successfully, it will return true. If
     * the data is not found, it will return false.
     */
    public function delete($id)
    {
        $data = $this->find($id, ['*'], [], true);
        return $data ? $data->delete() : false;
    }

    /**
     * This PHP function toggles the "active" status of a record and returns the updated status.
     * 
     * return bool a boolean value, which is the updated value of the "active" field of the record
     * after toggling it.
     */
    public function toggleActive(): bool
    {
        $record = $this->findOne(request('id'));

        $record->update([
            'active' => !$record->active
        ]);
        return $record->active;
    }

    /**
     * The function adds permissions to a role based on user input.
     * 
     * param permissions an array of permission objects that represent the permissions that can be
     * assigned to a role.
     * param role The  parameter is an instance of a Role model, which represents a role in a
     * system or application. It typically has a name and a set of permissions associated with it.
     * 
     * return the `` object after attaching the selected permissions to it.
     */
    public function addRole($permissions, $role)
    {
        //todo change
        foreach ($permissions as $permission) {
            if (request($permission->name) == "on") {
                $role->permissions()->attach($permission->id);
            }
        }
        return  $role;
    }

    /**
     * The function updates a role's permissions and type based on a request and saves the changes.
     * 
     * param permissions an array of permission objects that the role can have
     * param role The role parameter is an instance of a Role model, which represents a user role in
     * the system.
     * param request This is an object that contains the data sent in the HTTP request. It could be a
     * POST, PUT, or PATCH request, and it contains the data that needs to be updated for the role.
     * 
     * return the updated role object.
     */
    public function updateRole($permissions, $role, $request)
    {
        //todo change
        foreach ($permissions as $permission) {
            if ($request[$permission->name] == "on" && !$role->permissions->contains($permission)) {
                $role->permissions()->attach($permission->id);
            } elseif (!isset($request[$permission->name]) && $role->permissions->contains($permission)) {
                $role->permissions()->detach($permission->id);
            }
        }

        $request->type = $request->type ?? 0;
        $role->name = $request->name;
        $role->type = $request->type ?? 0;
        $role->save();
        return $role;
    }
}
