<?php

namespace Modules\MasterCatalog\Http\Resources\Profit;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperResource;

class ProfitResource extends JsonResource
{
    /**
     * The function returns an array of the id, profit, and dropshipper_id of the dropshipper
     * 
     * param request The incoming HTTP request.
     * 
     * return array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'profit' => (float) $this->profit ?? 0.0,
            'dropshipper' =>  new DropshipperResource($this->dropshipper),
        ];
    }
}
