<?php

namespace Modules\Acl\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    protected $fillable = ['name', 'active', 'type'];

    /**
     * This function defines a many-to-many relationship between the current model and the User model
     * in PHP.
     * 
     * return BelongsToMany A `BelongsToMany` relationship between the current model and the `User`
     * model is being returned. This indicates that the current model can have many users associated
     * with it, and each user can be associated with many instances of the current model.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * This function returns a many-to-many relationship between the current model and the Permission
     * model through the "permission_role" pivot table.
     * 
     * return BelongsToMany A `BelongsToMany` relationship between the current model and the
     * `Permission` model, using the `permission_role` pivot table.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }
}
