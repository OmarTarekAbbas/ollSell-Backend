<?php

namespace App\Actions\Order;

use Modules\Basic\Actions\BaseAction;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Repositories\OrderRepository;
use Modules\MasterCatalog\Service\ProductService;
use Modules\Store\Entities\DropshipperEcommerce;
use Modules\Store\Entities\DropshipperMappingProduct;
use Modules\Store\Entities\DropshipperMappingOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Acl\Entities\Dropshipper;

//todo move to integration module salla

/**
 * @property string merchant example "674390266"
 * @property string created_at example "2021-06-02 22:17:06"
 * @property string event example "order.created"
 * @property array data @see https://docs.salla.dev/docs/merchent/openapi.json/components/schemas/OrdersWebhookResponse
 */
class Created extends BaseAction
{
    protected $repo;

    public function __construct(OrderRepository $repository)
    {
        $this->repo = $repository;
    }

    public function handle()
    {
        $myOrder = 0;
        $dropshipper_id = DropshipperEcommerce::where('store_id', $this->request->merchant)
            ->where('store_type', 'salla')->first()->dropshipper_id;
        if($dropshipper_id)
        {
            $dropshipper = Dropshipper::find($dropshipper_id);
            Auth::guard('dropshipper')->setUser($dropshipper);
            $country_id = DB::table('translations')->where('category_type', 'Modules\CoreData\Entities\Country')
                ->where('value', 'like', '%' . $this->request->data['customer']['country'] . '%')->first()->category_id;
            $items = $this->request->data['items'];
            $totalPriceForProduct = [];
            $totalSellingPriceForProduct = [];
            $request_items = [];
            $k = 0;
            foreach($items as $item)
            {
                $product_id = DropshipperMappingProduct::where('dropshipper_id', $dropshipper_id)
                    ->where('model_type', 'salla')->where('model_id', $item['product']['id'])->first()->product_id;
                if($product_id)
                {
                    $myOrder = 1;
                    $product = app()->make(ProductService::class)->show($product_id);
                    $totalPriceForProduct[] = ($product->calculator() * $item['quantity']);
                    $totalSellingPriceForProduct[] = $item['amounts']['total']['amount'];
                    $request_items[$k]['product'] = $product_id;
                    $request_items[$k]['quantity'] = $item['quantity'];
                    $k++;
                }
            }
            if($myOrder)
            {
                $totalQuantity = collect($items)->sum('quantity');
                $countOrderItem = collect($items)->count();
                $totalPrice = collect($totalPriceForProduct)->sum();
                $costPrice = collect($totalSellingPriceForProduct)->sum();
                $shippingFees = 5;
                $request = new Request;
                $request->merge([
                    'shippingFees' => $shippingFees,
                    'shippingMethod' => 'shippingMethod',
                    'totalQuantity' => $totalQuantity,
                    'subTotal' => $totalPrice,
                    'phone_code' => $this->request->data['customer']['mobile_code'],
                    'grandTotal' => ($totalPrice + $shippingFees),
                    'dropshipper_id' => $dropshipper_id,
                    'status_id' => OrderEnum::NEW_STATUS,
                    'customerName' => $this->request->data['customer']['first_name'] . ' ' . $this->request->data['customer']['last_name'],
                    'customerPhone' => $this->request->data['customer']['mobile'],
                    'customerAddress' => "test address salla",
                    'customerLocation' => "test",
                    'country_id' => $country_id,
                    'customerCity' => DB::table('translations')
                        ->where('category_type', 'Modules\CoreData\Entities\City')
                        ->where('value', 'like', '%' . $this->request->data['customer']['city'] . '%')
                        ->first()->category_id,
                    'countOrderItem' => $countOrderItem,
                    'costPrice' => $costPrice,
                    'paymentMethod' => 1,
                ]);
                request()->merge([
                    'items' => $request_items
                ]);
                $data = $this->repo->save($request);
                $ordermaping = new DropshipperMappingOrder();
                $ordermaping->dropshipper_id = $dropshipper_id;
                $ordermaping->model_type = 'salla';
                $ordermaping->model_id = $this->request->data['id'];
                $ordermaping->order_id = $data->id;
                $ordermaping->save();
            }
        }
    }
}
