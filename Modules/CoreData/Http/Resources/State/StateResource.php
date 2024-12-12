<?php

namespace Modules\CoreData\Http\Resources\State;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CoreData\Http\Resources\City\CityResource;
use Modules\CoreData\Http\Resources\Country\CountryResource;

class StateResource extends JsonResource
{
    /**
     * This PHP function converts an object into an array with specific properties.
     * 
     * param request  is an instance of the Illuminate\Http\Request class which represents an
     * incoming HTTP request. It contains information about the request such as the HTTP method,
     * headers, and query parameters. However, in this particular code snippet, the  parameter
     * is not being used.
     * 
     * return An array with the properties of a certain object, including its id, name, status,
     * country, and city. The country and city properties are returned as instances of the
     * CountryResource and CityResource classes, respectively.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name->value ?? "",
            'status' => $this->status,
            'country' => new CountryResource($this->country),
            'city' => new CityResource($this->city),
        ];
    }
}
