<?php

namespace Modules\CoreData\Http\Resources\OnboardingCategory;

use Illuminate\Http\Resources\Json\JsonResource;

class OnboardingCategoryResource extends JsonResource
{
    /**
     * This PHP function converts an object into an array with specific key-value pairs.
     * 
     * param request  is an instance of the Illuminate\Http\Request class, which represents an
     * incoming HTTP request. It contains information about the request such as the HTTP method,
     * headers, and query parameters. In this context, it is being passed as an argument to the
     * toArray() method, which is used to convert a model
     * 
     * return An array with the user's ID, name, and avatar. If the user has an avatar, the function
     * will return the file path to the avatar. If the user does not have an avatar, it will return a
     * default blank avatar.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name->value ?? ""
        ];
    }
}
