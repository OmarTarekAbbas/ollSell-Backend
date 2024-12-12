<?php

namespace Modules\Logistics\Http\Resources\ShippingCompany;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingCompanyListResource extends JsonResource
{
    /**
     * This PHP function converts an object's properties into an array with specific keys and values.
     * 
     * param request  is an instance of the Illuminate\Http\Request class, which represents an
     * HTTP request. It contains information about the request such as the HTTP method, headers, and
     * query parameters. In this context, it is used as a parameter for the toArray() method to
     * generate an array representation of the object that can
     * 
     * return An array with the properties 'id', 'name', 'code', and 'logo'. The 'id' property is the
     * value of the object's 'id' property. The 'name' property is the value of the object's 'name'
     * property, or an empty string if it is null. The 'code' property is the uppercase value of the
     * object's 'code' property, or
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? "",

        ];
    }
}
