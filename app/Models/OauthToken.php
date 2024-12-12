<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//todo change
class OauthToken extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function hasExpired()
    {
        return now()->timestamp > $this->expires_in;
    }
}

