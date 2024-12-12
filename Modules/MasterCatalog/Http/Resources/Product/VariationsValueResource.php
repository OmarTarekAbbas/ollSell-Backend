<?php

namespace Modules\MasterCatalog\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class VariationsValueResource extends JsonResource
{
    /**
     * It returns the data in the form of an array.
     *
     * param request The incoming request.
     *
     * return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'option_name' => $this->attributeOption->name,
        ];
    }

}
