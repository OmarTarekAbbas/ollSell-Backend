<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Acl\Entities\Dropshipper;
use Modules\Acl\Entities\DropshipperBranch;

class Invoice extends Model
{

    /* A white list of attributes that are allowed to be mass assigned. */
    protected $fillable = [
        'order_id',
        'dropshipper_id',
        'dropshipper_branch_id',
        'invoice_number',
        'paymentMethod',
        'costPrice',
        'subTotal',
        'grandTotal',
        'totalVat',
        'net_profit',
        'pdf_link',
    ];

    /* Telling the model to use the profits table. */
    protected $table = 'invoices';
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];
    public $searchRelationShip = [];
    public static $rules = [];

    /**
     * This function returns the validation rules for the model.
     *
     * @return The rules for the model.
     */
    public static function getValidationRules()
    {
        return self::$rules;
    }

    /**
     * This function returns an array of all the keys that are used in the translation files.
     *
     * @return An array of the keys that are to be translated.
     */
    public static function translationKey()
    {
        return [];
    }

    /**
     * This order function returns the order that belongs to this order_item.
     *
     * @return The order() method returns the order that belongs to the order_id.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * This is a PHP function that returns a relationship between a model and a Dropshipper model.
     *
     * return The function `dropshipper()` is returning a `belongsTo` relationship between the current
     * model and the `Dropshipper` model, with the foreign key `dropshipper_id`.
     */
    public function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class, 'dropshipper_id');
    }

    /**
     * This is a PHP function that returns a relationship between a model and a Dropshipper model.
     *
     * return The function `dropshipper()` is returning a `belongsTo` relationship between the current
     * model and the `Dropshipper` model, with the foreign key `dropshipper_id`.
     */
    public function dropshipperBranch()
    {
        return $this->belongsTo(DropshipperBranch::class, 'dropshipper_branch_id');
    }
}
