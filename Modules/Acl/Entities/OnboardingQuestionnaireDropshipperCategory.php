<?php

namespace Modules\Acl\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\CoreData\Entities\OnboardingCategory;

/**
 * @method static updateOrCreate(array $array)
 */
class OnboardingQuestionnaireDropshipperCategory extends Model
{
    protected $fillable = [
        'dropshipper_id', 'target_market_id'
    ];

    /* `protected  = 'dropshipper_target_markets';` is setting the name of the database table
    that the `DropshipperTargetMarket` model is associated with. In this case, the table name is
    `dropshipper_target_markets`. This is useful when the table name does not follow Laravel's
    naming conventions (i.e. pluralized model name as table name). */
    protected $table = 'onboarding_questionnaire_dropshipper_categories';

    public $timestamps = true;
    protected $with = ['onboarding_category'];
    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];
    public $searchRelationShip = [];

    public function onboarding_category()
    {
        return $this->belongsTo(OnboardingCategory::class, 'onboarding_category_id');
    }
}
