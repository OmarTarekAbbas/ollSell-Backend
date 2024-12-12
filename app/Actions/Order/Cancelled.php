<?php

namespace App\Actions\Order;

use Illuminate\Support\Facades\Auth;
use Modules\Acl\Entities\Dropshipper;
use Modules\Basic\Actions\BaseAction;
use Modules\Store\Entities\DropshipperEcommerce;
use Modules\Store\Entities\DropshipperMappingOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Modules\Order\Enums\OrderEnum;
use Modules\Order\Repositories\OrderRepository;
//todo move to integration module salla
/**
 * @property string merchant example "674390266"
 * @property string created_at example "2021-06-02 22:17:06"
 * @property string event example "order.cancelled"
 * @property array data @see
 *     https://docs.salla.dev/docs/merchent/openapi.json/components/schemas/OrderShipmentCreatedWebhookResponse
 */
class Cancelled extends BaseAction
{
    protected $repo;
    public function __construct(OrderRepository $repository)
    {
        $this->repo = $repository;
    }
    public function handle()
    {
        $dropshipper= DropshipperEcommerce::where('store_id',$this->request->merchant)
        ->where('store_type','salla')->first();
        if($dropshipper){
            
            $dropshipperLogin=Dropshipper::find($dropshipper->dropshipper_id);
            Auth::guard('dropshipper')->setUser($dropshipperLogin);
            $mapping =DropshipperMappingOrder::where('dropshipper_id',$dropshipper->dropshipper_id)
            ->where('model_type','salla')
            ->where('model_id',$this->request->data['id'])
            ->first();
            if($mapping){
                $order = $this->repo->find($mapping->order_id);
          
             if($order->status_id == OrderEnum::NEW_STATUS || $order->status_id == OrderEnum::PENDING_STATUS){
                $request =  new Request([
                    'status_id' =>  OrderEnum::CANCELED_STATUS,
                    'cancelDate' => Carbon::now(),
                ]);
                $data = $this->repo->save($request, $order->id);

                if ($data) {
                    return true;
                }
        
                return false;
             }
            
            }
        }
        return true;
    }
}
