<?php

namespace Modules\CoreData\Http\Resources\Status;

use Illuminate\Http\Resources\Json\JsonResource;

class SimplifiedStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name->value ?? "",        // Simplified to include only the name value
            'title' => getStatusTitle($this->id),      // Status title based on the ID
        ];
    }
}
