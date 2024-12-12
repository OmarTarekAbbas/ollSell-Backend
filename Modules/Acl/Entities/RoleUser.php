<?php

namespace Modules\Acl\Entities;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleUser extends Model
{
    //todo change role user permission
    use HasFactory, SoftDeletes;

    /* `protected  = 'role_user';` is setting the name of the database table that the `RoleUser`
    model is associated with. In this case, it is setting the table name to `role_user`. */
    protected $table = 'role_user';

    /**
     * This PHP function defines a relationship between the current class and the User class.
     * 
     * return The `user()` function is returning a `belongsTo` relationship between the current model
     * and the `User` model. This means that the current model belongs to a user and has a foreign key
     * referencing the `id` column of the `users` table.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * This PHP function returns a relationship between the current object and a Role object.
     * 
     * return The `belongsTo` relationship between the current model and the `Role` model is being
     * returned. This means that the current model belongs to a single `Role` model, and the `Role`
     * model has many instances of the current model.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * This PHP function returns a many-to-many relationship between the current object and the
     * Permission class using the 'permission_role' table.
     * 
     * return The `permission()` function is returning a many-to-many relationship between the current
     * model and the `Permission` model, using the `permission_role` pivot table.
     */
    public function permission()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }
    

}
