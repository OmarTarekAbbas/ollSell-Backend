<?php

namespace Modules\Acl\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionRole extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'permission_role';

    /**
     * This is a PHP function that returns a belongsTo relationship with the Permission class.
     * 
     * return A relationship between the current model and the Permission model is being returned.
     * Specifically, a "belongsTo" relationship is being defined, indicating that the current model
     * belongs to a single instance of the Permission model.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
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
}
