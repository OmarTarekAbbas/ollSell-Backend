<?php

namespace Modules\Acl\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static updateOrCreate(array $array)
 */
class OnboardingQuestionnaireDropshipperSocial extends Model
{
    protected $fillable = [
        'dropshipper_id', 'social'
    ];

    /* `protected  = 'dropshipper_target_markets';` is setting the name of the database table
    that the `DropshipperTargetMarket` model is associated with. In this case, the table name is
    `dropshipper_target_markets`. This is useful when the table name does not follow Laravel's
    naming conventions (i.e. pluralized model name as table name). */
    protected $table = 'onboarding_questionnaire_dropshipper_socials';

    public $timestamps = true;

    /**
     * [columns that needs to has customed search such as like or where in]
     *
     * @var string[]
     */
    public $searchConfig = [];
    public $searchRelationShip = [];
}
