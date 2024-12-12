<?php

namespace App\Actions\Order;

use Modules\Order\Enums\OrderEnum;

use Modules\Basic\Actions\BaseAction;
use Modules\Order\Enums\PlatformEnum;
use Modules\Store\Entities\DropshipperEcommerce;
use Modules\Store\Entities\DropshipperMappingOrder;
use Illuminate\Http\Request;
use Modules\CoreData\Service\CityService;
use Modules\MasterCatalog\Entities\Product;
use Modules\Basic\Traits\validationRulesTrait;
use Modules\Order\Entities\Order;
use Modules\Order\Actions\Order\CheckDuplicatedOrderAction;
use Modules\Order\Service\OrderService;
use Illuminate\Support\Facades\Log;
//todo move to integration module salla
/**
 * @property string merchant example 674390266
 * @property string created_at example "2021-06-02 22:17:06"
 * @property string event example "order.updated"
 * @property array data @see https://docs.salla.dev/docs/merchent/openapi.json/components/schemas/OrdersWebhookResponse
 */
class Updated extends BaseAction
{    use validationRulesTrait;

    public Order $order;
    public function handle()
    {
        Log::channel('salla')->info('update:');
        $city='';
        $request = request();
        $service = app()->make(OrderService::class);
        $newData['order_id'] =$this->request->data['id'] ;
        $isInvalid = true;
        $itemArray=[] ;
      $oldecreated = DropshipperMappingOrder::where('model_type','salla')->where('model_id',$this->request->data['id'])->first();
        if($oldecreated == null){
            try
            {
        
                $dropshipper = DropshipperEcommerce::where('store_id', $this->request->merchant)
                ->where('store_type', 'salla')->first();
               
                if($dropshipper == null)
                {
                    $isInvalid = false;
                    $message[] = 'dropshipper not found';
                }
        
                if(isset($this->request->data['payment_method']) && $this->request->data['payment_method'] != 'cod')
                {
                    $isInvalid = false;
                    $message[] = 'wrong payment method';
        
                }
                if(isset($this->request->data['shipments'][0]['ship_to']['city']))
                {
                   
                    $oldCity =$this->request->data['shipments'][0]['ship_to']['city'];
                    $city = app()->make(CityService::class)->search(new Request(['alias' => $this->request->data['shipments'][0]['ship_to']['city']]));
                   
                }
                if(empty($city))
                {
                  
                    $isInvalid = false;
                    $message[] = "City not found";
                }
                if(isset($this->request->data['items']) && !isset($this->request->data['items'][0]['sku']))
                {
                    $isInvalid = false;
                    $message[] = 'not found products';
        
                }
                $items = $this->request->data['items'];
                foreach($items as $item)
                {
                    $product = Product::where('sku', $item['sku'])->first();
                    if(!$product)
                    {
                        $isInvalid = false;
                        $message[] = 'Product SKU not found: ' . $item['sku'];
                        break;
                     
                    }
                   
                    if(($item['amounts']['total']['amount']/$item['quantity'] ) <= $product->cost_price)
                    {
                        $isInvalid = false;
                        $message[] = 'The Selling Price must be greater than the Cost Price.';
                     
                    }
                    $totalPriceForProduct[] = $item['amounts']['total']['amount'] ;
                    $totalSellingPriceForProduct[] = ($product->cost_price * $item['quantity']);
                    $totalProductVatArray[] = $this->getOrderItemVatImport($product, $item);
                    $netProfitArray[] = $this->netProfitImport($product, $item);
                    $itemArray[] = [
                        'product' => $product->id,
                        'quantity' => $item['quantity'],
                        'sellingPrice' => ($item['amounts']['total']['amount']/$item['quantity'] ),
                        'variant' => [
                            0 => 'null',
                        ],
                    ];
                }
        
                if(!count($itemArray))
                {
                    $isInvalid = false;
                }else{
                    $totalQuantity = collect($itemArray)->sum('quantity');
                    $countOrderItem = collect($items)->count();
                    $totalPrice = collect($totalPriceForProduct)->sum();
                    $costPrice = collect($totalSellingPriceForProduct)->sum();
                    $shippingFees = setting('shipping_fee') ?? 25;
                    $totalProductVat = collect($totalProductVatArray)->sum();
                    $netProfit = collect($netProfitArray)->sum();
                }
        
        
                if(isset($this->request->data['shipments'][0]['ship_to']['phone']))
                {
                    $newData['customerPhone'] = $this->handlePhoneKSA($this->request->data['shipments'][0]['ship_to']['phone']);
                }
                if(isset($newData['customerPhone']) && strlen($newData['customerPhone']) != 10)
                {
                  
                    $message[] = 'The phone field must be 10 number.';
                    $isInvalid = false;
                }
                if($isInvalid)
                {
                   
                    $newData['customerCity'] = $city->id;
                    $newData['paymentMethod'] = $this->request->data['payment_method'] == 'cod' ? 2 : 1;
                    $newData['customerCountry'] = '178'; // Country set to Saudi Arabia (id:178)
                    $newData['phone_code'] = '996'; // Phone_code set as per country 996 for saudi arabia
                    $newData['dropshipper_id'] =@$dropshipper->dropshipper_id;
                    $newData['source_platform'] = PlatformEnum::SALLA_PLATFORM;
                    $newData['items'] =   $itemArray;
                    $newData['is_duplicated'] = false;
                    $newData['duplicated_order_ids'] = null;
                    $newData['fresh_duplicated'] = false;
                    $request->merge([
                        'is_duplicated'=>$newData['is_duplicated'],
                        'duplicated_order_ids'=>$newData['duplicated_order_ids'],
                        'fresh_duplicated'=>$newData['fresh_duplicated'],
                        'shippingFees' => $shippingFees,
                        'shippingMethod' => 'shippingMethod',
                        'totalQuantity' => $totalQuantity,
                        'subTotal' => $totalPrice,
                        'grandTotal' => ($totalPrice + $shippingFees),
                        'dropshipper_id' =>@$dropshipper->dropshipper_id,
                        'status_id' => OrderEnum::NEW_STATUS,
                        'customerName' =>  $this->request->data['shipments'][0]['ship_to']['name'],
                        'customerPhone' =>  $newData['customerPhone'] ,
                        'customerAddress' => $this->request->data['shipments'][0]['ship_to']['address_line'],
                        'customerLocation' => $this->request->data['shipments'][0]['ship_to']['latitude'].','.$this->request->data['shipments'][0]['ship_to']['longitude'],
                        'district' =>  $newData['customerCountry'],
                        'country_id' =>  $newData['customerCountry'],
                        'customerCity' =>  $newData['customerCity'],
                        'countOrderItem' => $countOrderItem,
                        'paymentMethod' =>  $newData['paymentMethod'],
                        'items' => $itemArray,
                        'costPrice' => $costPrice,
                        'phone_code' => 966,
                        'net_profit' => $netProfit,
                        'totalVat' => $totalProductVat,
                        'source_platform' => PlatformEnum::SALLA_PLATFORM
                    ]);
        
        
        
                    $duplicatedOrders = (new CheckDuplicatedOrderAction(
                        request: $request
                    ))->execute();
                    $request->merge([...$duplicatedOrders]);
                    if($request->fresh_duplicated)
                    {
                     
                        $message[] = 'Order is duplicated.';
                        $newData['message'] = implode(',', $message);
                        $this->handleOrderError($newData);
                    }
                    if($isInvalid)
                    {
                      
                       $request->merge($newData);
                        $this->order = $service->store($request);
                        $orderMaping = new DropshipperMappingOrder();
                        $orderMaping->dropshipper_id = @$dropshipper->dropshipper_id;
                        $orderMaping->model_type = 'salla';
                        $orderMaping->model_id = $this->request->data['id'];
                        $orderMaping->order_id = $this->order->id;
                        $orderMaping->save();
        
                    }else{
                        $newData['customerCity'] =$oldCity ?? null;
                        $newData['paymentMethod'] = 'cod';
                        $newData['message'] = implode(',', $message);
                        $this->handleOrderError($newData);
                    }
                }else
                {
                    $newData['message'] = implode(',', $message);
                    $this->handleOrderError($newData);
                }
            }catch(\Exception $exception)
            {
             
                $newData['customerCity'] =$oldCity ?? null;
                $newData['paymentMethod'] = 'cod';
                $newData['message'] = implode(',', $message ?? []);
                $this->handleOrderError($newData);
            }
        }
  
    }

    public function handleOrderError($data)
    {
        $newData = [
            'order_id'=> $data['order_id'] ?? null,
            'message' => $data['message'] ?? null,
            'name' => $data['customerName'] ?? null,
            "phone" => $data['customerPhone'] ?? null,
            'address' => $data['customerAddress'] ?? null,
            'district' => $data['customerAddress'] ?? null,
            'city' => $data['customerCity'] ?? null,
            'country' => 'KSA',
            'location' => null,
            'paymentMethod' => $data['paymentMethod'] ?? null,
         
        ];
        $directoryPath = public_path("missings/salla_order/" . today()->format('Y-m-d'));
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true);  // إنشاء الدليل بالأذونات المناسبة
        }
        // $csvFile = fopen(public_path("missings/salla_order/" . today()->format('Y-m-d') . "/orders_failed_rows.csv"),"a");
        $csvFile = fopen($directoryPath . "/orders_failed_rows.csv", "a");
        fputcsv($csvFile, $newData);
        fclose($csvFile);
    }

    public function getOrderItemVatImport($product, $item)
    {
        $itemSellingPrice = ($item['amounts']['total']['amount']/$item['quantity'] );
        $productBasePrice = $product->is_discount ? $product->priceAfterDiscount : $product->cost_price;
        $baseVat = $productBasePrice * setting('shipping_fee');
        $totalProfit = $itemSellingPrice - $productBasePrice;
        $profitVat = $totalProfit * setting('shipping_fee');
        $netProfit = $totalProfit - $profitVat;
        $totalVat = $baseVat + $profitVat;
        return ($totalVat * $item['quantity']);
    }
    
    public function netProfitImport($product, $item)
    {
        $netProfit = $this->totalProfitImport($product, $item) - $this->vatProfitImport($product, $item);
        return $netProfit;
    }

    public function totalProfitImport($product, $item)
    {
        $itemSellingPrice = ($item['amounts']['total']['amount']/$item['quantity'] );
        return ($itemSellingPrice  - $product->cost_price) * $item['quantity'];
    }

    public function vatProfitImport($product, $item)
    {
        $itemSellingPrice = ($item['amounts']['total']['amount']/$item['quantity'] );
        $totalProfit =  $itemSellingPrice  - $product->cost_price;
        return ($totalProfit * setting('shipping_fee')) * $item['quantity'];
    }
}
