<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Service\OrderService;
use Modules\Acl\Service\DropshipperService;

class OrderListenerController extends Controller
{
    private $service;
    private $dropshipperService;

    public function __construct(OrderService $Service, DropshipperService $dropshipperService)
    {
        $this->service = $Service;
        $this->dropshipperService = $dropshipperService;

    }//todo change

    public function orderCreated(Request $request)
    {
        $dropshipper =$this->dropshipperService->show($request->dropshipper_id);

        $saas_order = $request->order;
        $address = $request->address;

        $data = $this->setOrderData($saas_order, $address, $dropshipper);

        $newRequest = new Request();

        $newRequest->replace($data);

        $order = $this->service->storeFromSaas($newRequest);

        if ($order) {
            return $this->createResponse($order, 'A new order has added successfully');
        }

        return $this->unKnowError();
    }

    private function setOrderData($order, $address, $dropshipper)
    {
        $items = [];
        foreach ($order['items'] as $key => $item) {
            $items[] = [
                'product' => $item['id'],
                'quantity' => $item['qty_ordered'],
            ];
        }

        return [
            'paymentMethod' => $order['payment']['method'] == 'cashondelivery' ? 2 : 1,
            'items' => $items,
            'customerName' => $order['customer_first_name'] . ' ' . $order['customer_last_name'],
            'customerPhone' => $address ? $address->phone : $dropshipper->phone,
            'customerAddress' => $address ? $address->address1 : $dropshipper->phone,
            'customerLocation' => null,
            'customerCity' => 1,
            'phone_code' => 966,
            'customerCountry' => 1,
            'dropshipper_id' => $dropshipper->id,
        ];
    }
}
