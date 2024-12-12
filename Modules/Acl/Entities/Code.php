<?php

namespace Modules\Acl\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Code extends Model
{
    use HasFactory;
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    protected $fillable = ['code', 'dropshipper_id', 'expireResendCodeAt'];
    protected $table = 'codes';
    public $timestamps = true;
    public $searchRelationShip = [];
}
