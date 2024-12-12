<?php

namespace Modules\Acl\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory,SoftDeletes;

     /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    protected $fillable = [];
    
    /**
     * This function defines a many-to-many relationship between the current model and the Role model
     * in PHP, with timestamps.
     * 
     * return The `roles()` function is returning a many-to-many relationship between the current
     * model and the `Role` model, with timestamps enabled.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }
    
}
