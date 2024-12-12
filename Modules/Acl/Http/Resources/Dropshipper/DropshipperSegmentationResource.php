<?php

namespace Modules\Acl\Http\Resources\Dropshipper;
use Carbon\Carbon;
use Modules\CoreData\Entities\DropshipperSegmentation;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Enums\OrderEnum;
use Modules\Acl\Entities\Dropshipper;

class DropshipperSegmentationResource extends JsonResource
{
    /**
     * This is a PHP function that converts an object into an array with specific properties and
     * formats the profit value.
     *
     * param request The  parameter is an instance of the Illuminate\Http\Request class, which
     * represents the current HTTP request being handled by the application. It contains information
     * about the request such as the HTTP method, headers, and query parameters. In this context, it is
     * not being used in the toArray() method.
     *
     * return An array of data representing a user, including their ID, profit, store and merchant
     * names, email, phone number, first and second names, verification status, wallet balance, bank
     * account information, target market, token, data state, avatar, and store details.
     */
    public function toArray($request)
    {

        $countOrder=$this->order()
        ->whereNotNull('validated')
        ->where('created_at', '>=',  Carbon::now()->subDays(45))
        ->count();
        $countOrderCheck=($countOrder) ? $countOrder :1;
     
        $dropshipper = Dropshipper::find($this->id);
        $segmentatioUpdate=DropshipperSegmentation::where('from','<=',$countOrderCheck)->where('to','>=',$countOrderCheck)->orderBy('from', 'desc')->first();
        $segmentatioUpdate=($segmentatioUpdate) ? $segmentatioUpdate->id :1;
        $dropshipper->dropshipper_segmentation_id = $segmentatioUpdate;
        $dropshipper->save();




        $nextSegmentation=DropshipperSegmentation::where('from','>',$countOrderCheck)->orderBy('from', 'asc')->first();

        $residual=(@$nextSegmentation->from - $countOrder);
        if($residual < 0){
            $residual=0;

        }
        return [
            'id'=>$dropshipper->segmentation->id,
            'name'=>$dropshipper->segmentation->name->value ?? "",
            'from'=>$dropshipper->segmentation->from,
            'to'=>$dropshipper->segmentation->to,
            'count_orders'=>$countOrder,
            'residual'=>$residual,
            'nextCountOrder'=>@$nextSegmentation->from,
            'nextSegmentation'=>@$nextSegmentation->name->value ?? "",

        ];
    }

}
