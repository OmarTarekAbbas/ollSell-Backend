<?php

namespace Modules\Logistics\Http\Resources\ShippingCompany;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingCompanyResource extends JsonResource
{
    /**
     * The function converts an object's properties into an array with some modifications.
     * 
     * param request  is an instance of the Illuminate\Http\Request class, which represents an
     * HTTP request. It contains information about the request such as the HTTP method, headers, and
     * query parameters. In this function, it is not used directly, but it is a required parameter for
     * the toArray method when used in a Laravel
     * 
     * return An array with the properties of the object, including its ID, name, order, code, status,
     * and logo. The name and code properties are modified to ensure they are in the correct format.
     * The logo property is also included, but if it is null, an empty string is returned instead.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name->value ?? "",
            'order' => $this->order,
            'code' => strtoupper($this->code) ?? "",
            'status' => $this->status,
            'logo' =>  $this->logo ? getFile($this->logo->file ?? null, pathType()['ip'], getFileNameServer($this->logo)) : '',
        ];
    }
}
