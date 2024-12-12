<?php

namespace Modules\CoreData\Http\Resources\Country;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryListResource extends JsonResource
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
            'name' => $this->name->value ?? "",
            'code' => strtoupper($this->code) ?? "",
            'logo' =>  $this->logo ? getFile($this->logo->file ?? null, pathType()['ip'], getFileNameServer($this->logo)) : '',
        ];
    }
}
