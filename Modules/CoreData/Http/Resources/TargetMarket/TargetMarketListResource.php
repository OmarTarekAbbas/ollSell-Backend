<?php

namespace Modules\CoreData\Http\Resources\TargetMarket;

use Illuminate\Http\Resources\Json\JsonResource;

class TargetMarketListResource extends JsonResource
{
    /**
     * The function converts an object's properties into an associative array.
     * 
     * param request  is an instance of the Illuminate\Http\Request class, which represents an
     * HTTP request. It contains information about the request such as the HTTP method, headers, and
     * query parameters. However, in this particular code snippet, the  parameter is not used.
     * 
     * return An array with the keys 'id', 'name', and 'code', and their corresponding values from the
     * current object instance. If the 'value' property of the 'name' attribute is null or undefined,
     * an empty string is returned instead.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name->value ?? "",
            'code' => $this->code ?? "",
            'logo' => asset('country-flag/country-'.  $this->code .'.svg'),
        ];
    }
}
