<?php

namespace Modules\CoreData\Http\Resources\City;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CoreData\Http\Resources\Country\CountryResource;

class CityResource extends JsonResource
{
    /**
     * This PHP function converts an object into an array with specific properties.
     * 
     * param request  is an instance of the Illuminate\Http\Request class, which represents an
     * HTTP request. It contains information about the request such as the HTTP method, headers, and
     * query parameters. However, in this particular code snippet, the  parameter is not being
     * used.
     * 
     * return An array with the id, name, status, and country of a resource. The country is returned
     * as a new instance of the CountryResource class.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name->value ?? "",
            'status' => $this->status,
            'country' => new CountryResource($this->country),
        ];
    }
}
