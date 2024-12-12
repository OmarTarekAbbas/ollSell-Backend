<?php

namespace Modules\Basic\Http\Resources\Media;

use Illuminate\Http\Resources\Json\JsonResource;

class mediaResource extends JsonResource
{
    /**
     * The function converts an object into an array and returns its ID and file path.
     * 
     * param request  is an object that represents the incoming HTTP request. It contains
     * information about the request such as the HTTP method, headers, and any data sent in the request
     * body. In this code snippet, it is not used directly in the toArray() method.
     * 
     * return An array with the keys 'id' and 'file', where 'id' is the value of the object's 'id'
     * property and 'file' is the result of calling the 'getFile' function with the object's 'file'
     * property, a path determined by the object's 'type' property, and the result of calling the
     * 'getFileNameServer' function with the object as an argument
     */
    public function toArray($request)
    {
        if (in_array($this->type, [mediaType()['dm']])) {
            $path = pathType()['up'];
        } elseif (in_array($this->type, [mediaType()['am'], mediaType()['lm']])) {
            $path = pathType()['ip'];
        } else {
            $path = pathType()['ip'];
        }
        return [
            'id' => $this->id,
            'file' => getFile($this->file, $path, getFileNameServer($this)) ??  asset('dashboard') . '/assets/media/svg/avatars/blank.svg',
        ];
    }
}
