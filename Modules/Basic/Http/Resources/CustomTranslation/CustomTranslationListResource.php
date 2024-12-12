<?php

namespace Modules\Basic\Http\Resources\CustomTranslation;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomTranslationListResource extends JsonResource
{
    public static $wrap = null;

    /**
     * The function converts an object into an array with specific keys and values.
     * 
     * param request  is an instance of the Illuminate\Http\Request class, which represents an
     * HTTP request. It contains information about the request such as the HTTP method, headers, query
     * parameters, and request body. However, in this particular code snippet, the  parameter
     * is not being used.
     * 
     * return An array with the properties 'id', 'key', 'value', and 'status' of the current object.
     * The 'value' property is accessed using the null coalescing operator to avoid errors if it is not
     * set.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'value' => $this->value->value ?? "",
            'status' => $this->status,
        ];
    }
}
