<?php

namespace Modules\Acl\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static updateOrCreate(array $array)
 */
class DropshipperPayment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'dropshipper_id', 'bank_name', 'bank_address', 'swift_number', 'beneficiary_name', 'beneficiary_address',
        'beneficiary_mobile', 'iban', 'currency', 'account_number','is_main'
    ];
    protected $table = 'dropshipper_payments';
    public $timestamps = true;
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];
    public $searchRelationShip = [];
    protected static $rules = [
        'bank_name' => 'required|string|max:50',
        'bank_address' => 'required|string|max:50',
        'swift_number' => 'required|string|max:50',
        'beneficiary_name' => 'required|string|max:50',
        'beneficiary_address' => 'required|string|max:50',
        'beneficiary_mobile' => 'required|string|max:50',
        'currency' => 'required',
        'account_number' => 'required',
        'iban' => 'required',
    ];
    public static function getValidationRules()
    {
        return self::$rules;
    }
    public function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class);
    }
}
