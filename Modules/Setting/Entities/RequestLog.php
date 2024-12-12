<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ip_address', 'method', 'url', 'request_body', 'response_status', 'response_time',
        'user_agent', 'referer','error'];
    protected $table = 'request_logs';
    public $timestamps = true;
}
