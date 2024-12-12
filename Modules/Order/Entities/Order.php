<?php

namespace Modules\Order\Entities;

use App\Models\User;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Modules\Acl\Entities\Dropshipper;
use Modules\Acl\Entities\DropshipperBranch;
use Modules\CoreData\Entities\City;
use Modules\CoreData\Entities\Country;
use Modules\CoreData\Entities\Status;
use Modules\Finance\Entities\Transaction;
use Modules\Finance\Entities\Wallet;
use Modules\Logistics\Entities\ShippingCompanyCityTime;
use Modules\Order\Enums\OrderEnum;

class Order extends Model
{
    use Loggable;

    /* A white list of attributes that are allowed to be mass assigned. */
    /**
     * Next allowed status
     *
     * @const array
     */
    const NEXT_ORDER_STATUS = [
        OrderEnum::PAY_PENDING_STATUS => [OrderEnum::CANCELED_STATUS],
        OrderEnum::CANCELED_STATUS => [OrderEnum::ONHOLD_STATUS],
        OrderEnum::NEW_STATUS => [OrderEnum::PREPARING_STATUS, OrderEnum::PENDING_STATUS, OrderEnum::CANCELED_STATUS],
        OrderEnum::PENDING_STATUS => [OrderEnum::PREPARING_STATUS, OrderEnum::CANCELED_STATUS, OrderEnum::ONHOLD_STATUS],
        OrderEnum::ONHOLD_STATUS => [OrderEnum::PREPARING_STATUS, OrderEnum::CANCELED_STATUS, OrderEnum::PENDING_STATUS],
        OrderEnum::PENDING_INVENTORY_STATUS => [OrderEnum::ONHOLD_STATUS, OrderEnum::PREPARING_STATUS, OrderEnum::CANCELED_STATUS],
        OrderEnum::PREPARING_STATUS => [OrderEnum::CANCELED_STATUS],
        OrderEnum::SHIPPING_STATUS => [OrderEnum::COMPLETED_STATUS, OrderEnum::REJECTED_STATUS],
        OrderEnum::REJECTED_STATUS => [OrderEnum::SHIPPING_STATUS],
        OrderEnum::COMPLETED_STATUS => [OrderEnum::REFUND_REPLACEMENT_REQUESTED_STATUS],
        OrderEnum::REFUND_REPLACEMENT_REQUESTED_STATUS => [OrderEnum::REFUND_REPLACEMENT_REQUESTED_REJECTED_STATUS, OrderEnum::REFUND_PROGRESSING_STATUS, OrderEnum::REPLACEMENT_PROGRESSING_STATUS],
        OrderEnum::REFUND_PROGRESSING_STATUS => [OrderEnum::REFUND_BALANCE_STATUS, OrderEnum::REFUND_STATUS],
        OrderEnum::REFUND_BALANCE_STATUS => [OrderEnum::REFUND_STATUS],
        OrderEnum::REPLACEMENT_PROGRESSING_STATUS => [OrderEnum::COMPLETED_STATUS],
        OrderEnum::RETURN_BALANCE_STATUS => [OrderEnum::CANCELED_STATUS, OrderEnum::REJECTED_STATUS],
    ];


    const SALLA_ORDER_STATUS = [
        OrderEnum::NEW_STATUS => "under_review",
        OrderEnum::PENDING_STATUS => "under_review",
        OrderEnum::PREPARING_STATUS => "in_progress",
        OrderEnum::SHIPPING_STATUS => "delivering",
        OrderEnum::COMPLETED_STATUS => "delivered",
        OrderEnum::CANCELED_STATUS => "canceled",
        OrderEnum::REJECTED_STATUS => "restored",
    ];

    /* A white list of attributes that are allowed to be mass assigned. */
    protected $fillable = [
        'paymentMethod',
        'shippingMethod',
        'totalQuantity',
        'shippingFees',
        'grandTotal',
        'subTotal',
        'dropshipper_id',
        'status_id',
        'phone_code',
        'customerName',
        'customerPhone',
        'customerAddress',
        'customerLocation',
        'country_id',
        'customerCity',
        'countOrderItem',
        'costPrice',
        'cancelDate',
        'deliveryDate',
        'tracking_number',
        'pdf_label',
        'weight',
        'totalVat',
        'district',
        'net_profit',
        'duplicated_order_ids',
        'is_duplicated',
        'is_import',
        'operator_id',
        'assigned_at',
        'sub_status_id',
        'remark_id',
        'follow_order',
        'validated',
        'attempts_count',
        'ollops_attempts',
        'ollops_order_id',
        'sent_to_ollops_at',
        'ollops_confirmation_status',
        'ollops_token',
        'validated_by',
        'first_message_time',
        'second_message_time',
        'third_message_time',
        'easy_order_id',
        'dropshipper_branch_id',
        'checkOutId',
        'source_platform',
        'created_platform',
        'validation_operator_id',
        'token',
        'allow_discounts',
        'applied_discount',
    ];
    /* Telling the model to use the profits table. */
    protected $table = 'orders';

    /* Telling the model to use the timestamps created_at and updated_at. */
    public $timestamps = true;

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = ['created_at' => 'date', 'validated' => 'date'];
    public $searchRelationShip = [
        'product_id' => 'orderItems->product_id',
        'bundle_id' => 'orderItems->bundle_id'
    ];

    public static $rules = [
        'paymentMethod' => 'required|integer',
        'items' => 'required|array',
        'items*' => 'required|exists:products,id',
        'customerName' => 'required|min:3|max:50',
        'phone_code' => 'required',
        'customerPhone' => 'required|regex:/^05\d{8}$/',
        'customerAddress' => 'required',
        'customerLocation' => 'nullable|url',
        'customerCity' => 'required|exists:cities,id',
        'customerCountry' => 'required|exists:countries,id',
        'is_fake' => 'required'
    ];
    const WEBSITE_PLATFORM = 'website';
    const EASYORDER_PLATFORM = 'easy_order';
    const SALLA_PLATFORM = 'salla';
    const ADMIN_PLATFORM = 'admin';
    const TiKTOK_PLATFORM = 'tiktok';

    /**
     * This function returns the validation rules for the model.
     *
     * return The rules for the model.
     */
    public static function getValidationRules()
    {
        return self::$rules;
    }

    /**
     * This function returns an array of all the keys that are used in the translation files.
     *
     * return An array of the keys that are to be translated.
     */
    public static function translationKey()
    {
        return [];
    }

    /**
     * This function returns the country that belongs to the customer.
     *
     * return The country() method returns a relationship between the Customer model and the Country
     * model.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
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

    /**
     * This function returns a collection of all the orders that belong to this user.
     *
     * return A collection of Order objects.
     */
    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * The `orderLogs` function defines a relationship where a model has many `OrderLog` instances.
     *
     * @return The `orderLogs()` function is returning a relationship that indicates that the current
     *             model has many `OrderLog` records associated with it.
     */
    public function orderLogs()
    {
        return $this->hasMany(OrderLog::class);
    }

    /**
     * This is a PHP function that returns a relationship between the current object and a Status
     * object.
     *
     * return A relationship between the current model and the `Status` model, where the foreign key
     * `status_id` is used to link the two models.
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function subStatus()
    {
        return $this->belongsTo(SubStatus::class);
    }

    public function remark()
    {
        return $this->belongsTo(Remark::class);
    }

    /**
     * This function returns the country that belongs to the customer.
     *
     * return The country() method returns a relationship between the Customer model and the Country
     * model.
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'customerCity');
    }

    /**
     * This function returns a collection of OrderItem objects that are related to this Order object.
     *
     * return A collection of OrderItem objects.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * This function returns a collection of OrderStatus objects that are related to the current Order
     * object.
     *
     * return The OrderStatus model.
     */
    public function orderStatus()
    {
        return $this->hasMany(OrderStatus::class);
    }

    public function OrderStatusAymakan()
    {
        return $this->hasMany(OrderStatusAymakan::class)->orderBy('created_at', 'asc');
    }
    public function OrderStatusAymakanNo()
    {
        return $this->hasMany(OrderStatusAymakan::class);
    }
    /**
     * This PHP function returns a collection of Wallet objects associated with a specific instance of
     * a class.
     *
     * return A relationship between the current model and the Wallet model is being returned.
     * Specifically, a "hasMany" relationship is being established, indicating that the current model
     * can have multiple instances of the Wallet model associated with it.
     */
    public function wallet()
    {
        return $this->hasMany(Wallet::class);
    }

    public function refunds()
    {
        return $this->hasMany(OrderRefund::class);
    }

    /**
     * This PHP function returns a collection of transactions associated with a specific model.
     *
     * return A relationship between the current model and the Transaction model is being returned.
     * Specifically, a "hasMany" relationship is being established, indicating that the current model
     * can have multiple instances of the Transaction model associated with it.
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class)->with('user');
    }

    public function orderSchedules()
    {
        return $this->hasMany(OrderSchedule::class);
    }

    public function getFirstUnsatisfiedScheduleAttribute()
    {
        return $this->orderSchedules()->where('satisfied', false)->first();
    }

    public function operator()
    {
        return $this->belongsTo(User::class);
    }

    public function validationOperator()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * This function returns a collection of all the orders that belong to this user.
     *
     * return A collection of Order objects.
     */
    public function attemptsLog()
    {
        return $this->hasMany(AttemptsLog::class);
    }

    /**
     * Get upcoming scheule for order
     *
     * Usage: OrderEnum::withUpcomingSchedule()->find($orderId);
     * Usage: OrderEnum::withUpcomingSchedule()->get();
     */
    public function scopeWithUpcomingSchedule($query)
    {
        return $query->with(['orderSchedules' => function ($query) {
            $query->where('scheduled_date', '>', now())->orderBy('scheduled_date');
        }])->with(['orderSchedules' => function ($query) {
            $query->where('scheduled_date', '>', now())->orderBy('scheduled_date');
        }]);
    }

    public function nextPossibleStatuses()
    {
        $currentStatusId = $this->status->id;
        $nextStatusIds = isset(self::NEXT_ORDER_STATUS[$currentStatusId]) ? self::NEXT_ORDER_STATUS[$currentStatusId] : [];
        // Fetch status objects for the next possible status IDs
        $nextStatuses = Status::whereIn('id', $nextStatusIds)->get();
        $deliveryDate = \Carbon\Carbon::parse($this->deliveryDate);
        $today = \Carbon\Carbon::today();
        if ($deliveryDate->diffInDays($today) < 7) {
            // Add order to not updated list
            return $nextStatuses;
        }
        return [];
    }

    public function wms_status()
    {
        return $this->hasMany(WmsOrderStatus::class);
    }

    public function delivery_duration()
    {
        $hours =  ShippingCompanyCityTime::where('city_id', $this->city->id)->first()->number_hours  ?? 0;
        return 'from ' . (int)(($hours + 24) / 24) . ' days to ' . (int)(($hours + 48) / 24) . ' days';
    }

    public function discounts()
    {
        return $this->hasMany(DiscountLog::class, 'order_id');
    }
}
