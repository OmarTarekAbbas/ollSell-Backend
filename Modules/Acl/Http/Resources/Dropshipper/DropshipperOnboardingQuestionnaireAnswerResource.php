<?php

namespace Modules\Acl\Http\Resources\Dropshipper;

use Illuminate\Http\Resources\Json\JsonResource;

class DropshipperOnboardingQuestionnaireAnswerResource extends JsonResource
{
    /**
     * This is a PHP function that converts an object into an array with specific properties and
     * formats the profit value.
     *
     * param request The  parameter is an instance of the Illuminate\Http\Request class, which
     * represents the current HTTP request being handled by the application. It contains information
     * about the request such as the HTTP method, headers, and query parameters. In this context, it is
     * not being used in the toArray() method.
     *
     * return An array of data representing a user, including their ID, profit, store and merchant
     * names, email, phone number, first and second names, verification status, wallet balance, bank
     * account information, target market, token, data state, avatar, and store details.
     */
    public function toArray($request)
    {
        return [
            'is_old_dropshipper'=>$this->is_old_dropshipper,
            'number_years_dropshipper'=>$this->number_years_dropshipper,
            'questionnaire_target_markets'=>$this->onboarding_questionnaire_dropshipper_target_markets->pluck('target_market.id')->toArray(),
            'questionnaire_dropshipper_social'=>$this->onboarding_questionnaire_social()->pluck('social')->toArray(),
            'cost_month_dropshipper'=>$this->cost_month_dropshipper,
            'questionnaire_dropshipper_onboarding_category'=>$this->onboarding_questionnaire_dropshipper_onboarding_category->pluck('onboarding_category.id')->toArray(),
        ];
    }

}
