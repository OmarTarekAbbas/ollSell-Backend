<?php

namespace Modules\Store\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDomain extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'user_domain';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
