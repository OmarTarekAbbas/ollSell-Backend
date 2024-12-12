<?php

namespace Modules\MasterCatalog\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
//todo change

class UsedAttributeListResource extends JsonResource
{
    public $attribute;
    public function __construct($attribute)
    {
        $this->attribute = $attribute;
    }
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
            'id' => $this->attribute->id,
            'option' => $this->attribute->attributeOption->name,
            'attribute' => $this->attribute->attribute->name
            // 'options' => AttributeOptionListResource::collection($this->attribute->options),
        ];
    }
}
