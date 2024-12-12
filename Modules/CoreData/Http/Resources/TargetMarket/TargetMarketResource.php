<?php

namespace Modules\CoreData\Http\Resources\TargetMarket;

use Illuminate\Http\Resources\Json\JsonResource;

class TargetMarketResource extends JsonResource
{
    /**
     * The function converts an object's properties into an associative array.
     * 
     * param request  is an instance of the Illuminate\Http\Request class, which represents an
     * HTTP request. It contains information about the request such as the HTTP method, headers, and
     * query parameters. However, in this particular code snippet, the  parameter is not used.
     * 
     * return An array with the properties of the object, including id, name, order, code, and status.
     * If the name or code properties are null, an empty string is returned instead.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name->value ?? "",
            'order' => $this->order,
            'code' => $this->code ?? "",
            'status' => $this->status,
        ];
    }
}
