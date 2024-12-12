<?php

namespace Modules\Acl\Entities;

use App\Models\OauthToken;
use Modules\Basic\Entities\Media;
use Modules\CoreData\Entities\DropshipperSegmentation;
use Modules\Order\Entities\Cart;
use Modules\Order\Entities\Order;
use Laravel\Passport\HasApiTokens;
use Modules\Store\Entities\UserDomain;
use Modules\Subscription\Entities\Plan;
use Illuminate\Notifications\Notifiable;
use Modules\Finance\Entities\Transaction;
use Modules\Order\Entities\RefundMessage;
use Modules\MasterCatalog\Entities\Profit;
use Modules\CoreData\Entities\Notification;
use Modules\CoreData\Entities\TargetMarket;
use Modules\MasterCatalog\Entities\Product;
use Modules\Finance\Entities\DepositRequest;
use Modules\MasterCatalog\Entities\Favorite;
use Modules\Finance\Entities\WithdrawalRequest;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Finance\Entities\ChatWithdrawalRequest;
use Modules\Order\Entities\Invoice;
use Modules\Order\Entities\PendingOrder;
use Modules\StoreIntegrations\Entities\SallaToken;
use Modules\Store\Entities\DropshipperEcommerce;

class Dropshipper extends Authenticatable
{
    use HasApiTokens, Notifiable, Loggable;

    protected $fillable = [
        'status',
        'store_name',
        'merchant_name',
        'email',
        'phone',
        'password',
        'mega',
        'email_verification',
        'token',
        'code_country',
        'profit',
        'lang',
        'code',
        'isVerified',
        'first_name',
        'second_name',
        'walletBalance',
        'plan_id',
        'expirePlanAt',
        'totalNotifications',
        'profitBalance',
        'onboarding_questionnaire_number',
        'earningsWithdrawal',
        'is_old_dropshipper',
        'number_years_dropshipper',
        'dropshipper_segmentation_id',
        'cost_month_dropshipper',
        'blocked',
        'extra_product_feature_enabled',
        'product_price_percentage',
        'max_discount',
    ];
    protected $table = 'dropshippers';
    public $timestamps = true;
    public $searchRelationShip = [];
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = ['created_at' => 'date'];
    public static $ruleStepOne = [
        'first_name' => 'required',
        'email' => 'required|email|unique:dropshippers',
        'phone' => 'required|string|digits_between:9,15|unique:dropshippers',
    ];
    public static $ruleStepTwo = [
        'code' => 'required',
    ];
    public static $ruleStepThree = [
        'store_name' => 'nullable|string|max:50',
        'first_name' => 'nullable|string|max:50',
        'second_name' => 'nullable|string|max:50',
        'target_market' => 'required|array|exists:target_markets,id',
    ];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    protected static $PasswordRules = [
        'password' => 'required|min:8|confirmed'
    ];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    protected static $rulesLogin = [
        'email' => 'required|email|exists:dropshippers,email',
        'password' => 'required|min:8|max:24'
    ];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    protected static $rulesProfit = [
        'profit' => 'nullable|numeric|gt:0.00|max:100.00',
    ];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    protected static $rulesCreateChangePhoneNumber = [
        'phone' => 'required|string|digits_between:10,15|unique:dropshippers',
    ];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public static $ruleUpdateProfile = [
        'first_name' => 'nullable|string|max:50',
        'second_name' => 'nullable|string|max:50',
        'avatar' => 'image|mimes:jpg,jpeg,png,gif',
    ];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public static $ruleUpdatePassword = [
        'password' => 'required|min:8|confirmed',
        'old_password' => 'required|min:8',
    ];
    const MEGA_PROFIT = 10;

    /**
     * The function returns an array of validation rules by merging two arrays.
     *
     * return An array that is the result of merging two arrays: `self::` and
     * `self::`.
     */
    public static function getValidationRules()
    {
        return array_merge(self::$ruleStepOne, self::$PasswordRules);
    }

    /**
     * This is a static function in PHP that returns a validation rule for step two.
     *
     * return The method `getValidationRuleTwo()` is returning the value of the static property
     * ``.
     */
    public static function getValidationRuleTwo()
    {
        return self::$ruleStepTwo;
    }

    /**
     * This is a static function in PHP that returns a validation rule for step three.
     *
     * return The method `getValidationRuleThree()` is returning the value of the static property
     * ``.
     */
    public static function getValidationRuleThree()
    {
        return self::$ruleStepThree;
    }

    /**
     * This function returns the validation rules for creating or changing a phone number in PHP.
     *
     * return The method `getValidationCreateChangePhoneNumber()` is returning the value of the static
     * property ``.
     */
    public static function getValidationCreateChangePhoneNumber()
    {
        return self::$rulesCreateChangePhoneNumber;
    }

    /**
     * This function returns the validation rules for updating data.
     *
     * return The method `getValidationRulesUpdate()` is returning the static property ``.
     */
    public static function getValidationRulesUpdate()
    {
        return self::$ruleStepOne;
    }

    /**
     * The function returns the validation rules for login in PHP.
     *
     * return The method `getValidationRulesLogin()` is returning the value of the static property
     * ``.
     */
    public static function getValidationRulesLogin()
    {
        return self::$rulesLogin;
    }

    /**
     * The function returns the validation rules for profit in PHP.
     *
     * return The method `getValidationRulesProfit()` is returning the static property ``.
     */
    public static function getValidationRulesProfit()
    {
        return self::$rulesProfit;
    }

    /**
     * This function returns the validation rule for updating a user's profile in PHP.
     *
     * return The function `getValidationRuleUpdateProfile()` is returning the value of the static
     * property ``.
     */
    public static function getValidationRuleUpdateProfile()
    {
        return self::$ruleUpdateProfile;
    }

    /**
     * This function returns the validation rule for updating a password in PHP.
     *
     * return The method `getValidationRuleUpdatePassword()` is returning the value of the static
     * property ``.
     */
    public static function getValidationRuleUpdatePassword()
    {
        return self::$ruleUpdatePassword;
    }

    /**
     * This PHP function defines a many-to-many relationship between a model and a target market model.
     *
     * return A many-to-many relationship between the current model and the TargetMarket model, using
     * the pivot table 'dropshipper_target_markets'.
     */
    public function targetMarket()
    {
        return $this->belongsToMany(TargetMarket::class, 'dropshipper_target_markets');
    }

    /**
     * This PHP function returns a collection of DropshipperTargetMarket objects associated with a
     * specific instance.
     *
     * return A hasMany relationship between the current model and the DropshipperTargetMarket model
     * is being returned.
     */
    public function dropshipperTargetMarket()
    {
        return $this->hasMany(DropshipperTargetMarket::class);
    }

    public function accountType(): string
    {
        return 'Modules\Acl\Entities\Dropshipper';
    }

    /**
     * This function deletes related data when a model is deleted.
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($data) {
            $data->dropshipperTargetMarket()->delete();
            $data->profits()->delete();
            $data->favorite()->delete();
            $data->media()->delete();
        });
    }

    /**
     * This function returns a relationship between the current model and the Profit model.
     *
     * return The relationship between the two models.
     */
    public function profits()
    {
        return $this->hasMany(Profit::class);
    }

    /**
     * A user can have many products, and a product can have many users.
     *
     * return A collection of products.
     */
    public function Product()
    {
        return $this->belongsToMany(Product::class, 'favorites');
    }

    /**
     * This product has many favorites.
     *
     * return The hasMany relationship is being returned.
     */
    public function favorite()
    {
        return $this->belongsTo(Favorite::class);
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
        return $this->hasMany(Transaction::class);
    }

    /**
     * This function defines a one-to-many relationship between the current model and the
     * WithdrawalRequest model in PHP.
     *
     * return A relationship between the current model and the WithdrawalRequest model is being
     * returned. Specifically, a "hasMany" relationship is being established, indicating that the
     * current model can have multiple instances of the WithdrawalRequest model associated with it.
     */
    public function withdrawalRequest()
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    /**
     * This function defines a one-to-many relationship between the current model and the
     * WithdrawalRequest model in PHP.
     *
     * return A relationship between the current model and the WithdrawalRequest model is being
     * returned. Specifically, a "hasMany" relationship is being established, indicating that the
     * current model can have multiple instances of the WithdrawalRequest model associated with it.
     */
    public function depositRequest()
    {
        return $this->hasMany(DepositRequest::class);
    }

    public function payments()
    {
        return $this->hasMany(DropshipperPayment::class);
    }

    /**
     * This function returns a collection of all the orders that belong to this user.
     *
     * return A collection of Order objects.
     */
    public function order()
    {
        return $this->hasMany(Order::class)->orderBy('id', 'desc');
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
     * This function returns a collection of all the Dropshipper Branch that belong to this Dropshipper.
     *
     * return A collection of Order objects.
     */
    public function DropshipperBranch()
    {
        return $this->hasMany(DropshipperBranch::class)->orderBy('id', 'desc');
    }

    public function DropshipperOption()
    {
        return $this->hasMany(DropshipperOption::class)->orderBy('id', 'desc');
    }

    /**
     * This function returns a morphOne relationship with the Media model under the category attribute.
     *
     * return The `media()` function is returning a `morphOne` relationship between the current model
     * and the `Media` model, where the `category` column of the `Media` table is used to store the
     * type of the related model.
     */
    public function media()
    {
        return $this->morphOne(Media::class, 'category');
    }

    /**
     * This PHP function returns the avatar media of an object.
     *
     * return The `avatar()` function is returning a query builder instance that filters the media
     * associated with the current model instance to only include those with a type of
     * `mediaType()['am']`.
     */
    public function avatar()
    {
        return $this->media()->whereType(mediaType()['am']);
    }

    /**
     * The function returns a relationship between the current object and a UserDomain object.
     *
     * return A relationship between the current model and the UserDomain model is being returned.
     * Specifically, a "hasOne" relationship is being established, indicating that the current model
     * has one instance of the UserDomain model associated with it.
     */
    public function store()
    {
        return $this->hasOne(UserDomain::class);
    }

    public function dropshipperEcommerces()
    {
        return $this->hasMany(DropshipperEcommerce::class);
    }

    // public function sallatoken()
    // {
    //     return $this->hasOne(OauthToken::class);
    // }
    public function sallaToken()
    {
        return $this->hasOne(SallaToken::class);
    }

    public function segmentation()
    {
        return $this->belongsTo(DropshipperSegmentation::class, 'dropshipper_segmentation_id');
    }

    /**
     * Get all of the Refund Message.
     */
    public function refundMessages()
    {
        return $this->morphMany(RefundMessage::class, 'messagable');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'user');
    }

    public function onboarding_questionnaire_target_markets()
    {
        return $this->belongsToMany(
            OnboardingQuestionnaireDropshipperTargetMarket::class,
            'onboarding_questionnaire_dropshipper_target_markets',
            'dropshipper_id',
            'target_market_id'
        );
    }

    public function onboarding_questionnaire_dropshipper_target_markets()
    {
        return $this->hasMany(
            OnboardingQuestionnaireDropshipperTargetMarket::class,
            'dropshipper_id'
        );
    }

    public function onboarding_questionnaire_dropshipper_social()
    {
        return $this->belongsToMany(
            OnboardingQuestionnaireDropshipperSocial::class,
            'onboarding_questionnaire_dropshipper_socials',
            'dropshipper_id',
            'social'
        );
    }

    public function onboarding_questionnaire_social()
    {
        return $this->hasMany(
            OnboardingQuestionnaireDropshipperSocial::class,
            'dropshipper_id'
        );
    }

    public function onboarding_questionnaire_category()
    {
        return $this->belongsToMany(
            OnboardingQuestionnaireDropshipperCategory::class,
            'onboarding_questionnaire_dropshipper_categories',
            'dropshipper_id',
            'onboarding_category_id'
        );
    }

    public function onboarding_questionnaire_dropshipper_onboarding_category()
    {
        return $this->hasMany(
            OnboardingQuestionnaireDropshipperCategory::class,
            'dropshipper_id'
        );
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'dropshipper_id');
    }

    /**
     * This function returns a collection of all the orders that belong to this user.
     *
     * return A collection of Order objects.
     */
    public function pendingOrder()
    {
        return $this->hasMany(PendingOrder::class);
    }

    public function DropshipperOptionCheck($optionName)
    {
        $dropshipper_setting = DropshipperSetting::where('name', $optionName)->first();
        if ($dropshipper_setting) {
            return $this->DropshipperOption->where('dropshipper_setting_id', $dropshipper_setting->id)->count();
        }
        return false;
    }

    /**
     * The `chats` function returns a polymorphic relationship with `ChatWithdrawalRequest` model as
     * the related model.
     * 
     * @return The `chats()` function is returning a morphMany relationship with the
     * `ChatWithdrawalRequest` model using the `messagable` polymorphic relationship. This means that
     * the function will return all chat withdrawal requests associated with the current model
     * instance.
     */
    public function chats()
    {
        return $this->morphMany(ChatWithdrawalRequest::class, 'messagable');
    }
}
