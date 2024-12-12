<?php

namespace Modules\CoreData\Http\Resources\Language;

use Illuminate\Http\Resources\Json\JsonResource;

class LanguageListResource extends JsonResource
{
    /**
     * The function converts an object's properties into an associative array.
     * 
     * param request  is an instance of the Illuminate\Http\Request class, which represents an
     * HTTP request. It contains information about the request such as the HTTP method, headers, and
     * query parameters. However, in this particular code snippet, the  parameter is not being
     * used.
     * 
     * return An array with the properties "id", "name", and "code" of the current object.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
        ];
    }
}
