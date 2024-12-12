<?php

namespace Modules\Order\Actions\Order;

use Modules\MasterCatalog\Actions\Product\CheckQuantityAction;
use Modules\Order\Enums\OrderEnum;
use Illuminate\Support\Facades\Log;
use Modules\CoreData\Entities\City;
use Modules\MasterCatalog\Entities\Product;
use Illuminate\Http\Client\RequestException;
use Modules\Order\Repositories\OrderRepository;

class CreateShipmentOrderAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function executes an order by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the order data.
     *
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     *
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute($data)
    {
        $check = App(CheckQuantityAction::class)->execute($data);
        if ($check == false) {
            if ($data->status_id != OrderEnum::PENDING_INVENTORY_STATUS) {
                $data->update(['status_id' => OrderEnum::PENDING_INVENTORY_STATUS]);
            }
            return false;
        }

        $city = City::find($data->customerCity);
        $description = '';
        foreach ($data->orderItems as $item) {
            $product = Product::find($item->product_id);
            $productObject = json_decode($item->product_json);
            $description = $description == '' ? '' : $description;
            $description .= ' (' . $item['quantity'] . ') ' . ($productObject == null ? $product->nameValue(2) : $productObject->product_name);
        }
        $requestAymakan = [
            'fulfilment_customer_name' => 'OLLOPS',
            'requested_by' => 'OLLOPS',
            'declared_value' => $data->grandTotal,
            'delivery_name' => $data->customerName,
            'declared_value_currency' => 'SAR',
            "reference" => $data->id . (setting('AYMKAN_DEBUG') == "true" ? '-' . rand(1, 9999) : ''),
            "is_cod" => $data->paymentMethod,
            "cod_amount" => ($data->paymentMethod == 2) ? $data->grandTotal : 0,
            'currency' => 'SAR',
            "delivery_neighbourhood" => '',
            "delivery_description" => $description,
            'delivery_city' => $city->name?->value,
            'delivery_address' => $data->customerAddress,
            'delivery_country' => "SA",
            'delivery_phone' => $data->customerPhone,
            'collection_name' => 'OLLOPS',
            'collection_city' => "Riyadh",
            "collection_neighbourhood" => 'neighbourhood',
            'collection_address' => env('COLLECTION_ADDRESS', 'Riyadh, Saudi Arabia'),
            'collection_country' => "SA",
            // "collection_postcode" => '1234',
            "collection_description" => $description,
            "weight" => (float)$data->weight,
            'collection_phone' => env('COLLECTION_PHONE', '0553830040⁩'),
            'pieces' => 1,
            'items_count' => $data->totalQuantity,
            // data error
            'invoice_number' => $data->id,
            'invoice_date' => $data->created_at->format('Y-m-d'),
        ];
        $request = request();

        try {
            $aymakan = App(AymakanCreateShipmentOrderAction::class)->execute($requestAymakan);
            
            // Check if the response is successful
            if (is_array($aymakan) && isset($aymakan['shipping']['tracking_number'])) {
                if (!empty($aymakan['shipping']['tracking_number'])) {
                    $request->merge([
                        'tracking_number' => $aymakan['shipping']['tracking_number'],
                        'pdf_label' => isset($aymakan['shipping']['pdf_label']) ? $aymakan['shipping']['pdf_label'] : '',
                        'status_id' => OrderEnum::PREPARING_STATUS,
                        'first_message_time' => $data->first_message_time,
                        'second_message_time' => $data->second_message_time,
                        'third_message_time' => $data->third_message_time
                    ]);
                    $data = $this->repo->save($request, $data->id);
                    $data->update(['status_id' => OrderEnum::PREPARING_STATUS]);
                    return  $data;
                }
            } else {
                $aymakanCheck = App(AymakanCreateShipmentOrderAction::class)->getOrderByReference($data);
                if (!$aymakanCheck) {
                    Log::error('Unexpected format for $aymakan: ' . print_r($aymakan, true));
                }
            }
        } catch (RequestException $e) {
            // Handle HTTP request errors
            if ($e->response->status() === 422) {
                $responseBody = $e->response->json();
                // Log or handle the error message
                Log::error('Aymakan API returned 422 Unprocessable Entity: ' . $responseBody['message']);
            } else {
                // Handle other HTTP request errors
                Log::error('Aymakan API request failed with status code: ' . $e->response->status());
            }
            $failData['taggable_id'] = $data->id;
            $failData['status'] = @$e->response->status();
            $failData['reason'] = @$responseBody['message'];
            $failData['taggable_type'] = "Modules\Order\Entities\Order";
            $failData['payload'] = @$responseBody;
            $failData['type'] = 'Aymakan';
            App(FailOrderAction::class)->execute($failData);
        } catch (\Exception $e) {
            // Handle other exceptions
            $failData['taggable_id'] = $data->id;
            $failData['status'] =  @$e->getCode();
            $failData['reason'] = @$e->getMessage();
            $failData['taggable_type'] = "Modules\Order\Entities\Order";
            $failData['type'] = 'Aymakan';
            App(FailOrderAction::class)->execute($failData);
            Log::error('An error occurred while processing Aymakan API request: ' . $e->getMessage());
        }
    }
}
