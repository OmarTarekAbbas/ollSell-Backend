<?php

namespace Modules\StoreIntegrations\Entities;

use Carbon\Carbon;
use Modules\Acl\Entities\Dropshipper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SallaToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'dropshipper_id',
        'merchant_id',
        'access_token',
        'refresh_token',
        'expires_at',
        'store_name',
        'store_domain',
    ];

    protected $dates = ['expires_at'];

    public function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class);
    }

    public function isExpired()
    {
        return false;
    }

    public function refreshToken($newAccessToken, $newRefreshToken, $expiresInSeconds)
    {
        $this->access_token = $newAccessToken;
        $this->refresh_token = $newRefreshToken;
        $this->expires_at = Carbon::now()->addSeconds($expiresInSeconds);

        $this->save();
    }
}