<?php

namespace App\Jobs;
//todo move to integration module salla
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class SendProductSalla implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $products;
    private $dropshipper_id;
    private $token;

    /**
     * Create a new job instance.
     *
     * return void
     */
    public function __construct($products, $dropshipper_id, $token)
    {

        $this->products = $products;
        $this->dropshipper_id = $dropshipper_id;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * return void
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();
        foreach ($this->products as $row) {
            try {
                $product = DB::table('dropshipper_mapping_products')->where('dropshipper_id', $this->dropshipper_id)->where('model_type', 'salla')->where('product_id', $row->id)->select('id', 'model_id', 'move')->first();
                $vairationsAtrributesValues = $row->productVariantValues;
                $usedAttributesOptions = [];
                foreach ($vairationsAtrributesValues as $index => $vairationsAtrributesValue) {
                    $usedAttributesOptions[$vairationsAtrributesValue->attribute->name][$index] = $vairationsAtrributesValue->attributeOption->name;
                    $usedAttributesOptions[$vairationsAtrributesValue->attribute->name] = array_unique($usedAttributesOptions[$vairationsAtrributesValue->attribute->name]);
                }

                if ($product) {

                    if ($product->move == 0) {


                        $sortedProduct = $this->getProductData($row, $usedAttributesOptions);

                        $this->deleteOption($row);
                        $this->addOption($row, $usedAttributesOptions, $product);

                        $apiRequest = $client->request('PUT', 'https://api.salla.dev/admin/v2/products/' . $product->model_id, [
                            'body' => json_encode($sortedProduct),
                            'headers' => [
                                'Accept' => 'application/json',
                                'Authorization' => $this->token,
                                'Content-Type' => 'application/json',
                            ],
                        ]);
                        $response = json_decode($apiRequest->getBody());

                        if ($response->success == true) {

                            DB::table('dropshipper_mapping_products')
                                ->where('id', $product->id)
                                ->update(['move' => 1]);

                            $salla_product = $response->data;
                            DB::table('dropshipper_mapping_products_skus')->where('model_type', 'salla')->where('product_id', $row->id)->where('dropshipper_id', $this->dropshipper_id)->delete();

                            if ($salla_product->skus) {

                                $product = $row;
                                $variants = [];
                                $k = 0;
                                foreach ($product->productVariants as $value) {
                                    $variants[$k]['id'] = $value->id;
                                    $variants[$k]['price'] = $value->price;
                                    $variants[$k]['quantity']  = $value->quantity;
                                    $variants[$k]['sku']  = $value->sku;
                                    $y = 0;
                                    foreach ($value->productVariantValue as $row) {
                                        $variants[$k]['variants_values'][$y]['id'] = $row->id;
                                        $variants[$k]['variants_values'][$y]['option_name'] = $row->attributeOption->name;
                                        $y++;
                                    }

                                    $k++;
                                }
                                $product->variants = $variants;
                                $this->mapingVarartion($salla_product, $product);
                            }
                        }
                    }
                } else {


                    $sortedProduct = $this->getProductData($row, $usedAttributesOptions);
                    $apiRequest = @$client->request('POST', 'https://api.salla.dev/admin/v2/products', [
                        'body' => json_encode($sortedProduct),
                        'headers' => [
                            'Accept' => 'application/json',
                            'Authorization' => $this->token,
                            'Content-Type' => 'application/json',
                        ],
                    ]);
                    $response = json_decode($apiRequest->getBody());
                    if ($response->success == true) {
                        //  $this->mappingervice->storeMapping($response->data,$product);
                        $salla_product = $response->data;
                        DB::table('dropshipper_mapping_products')->insert(
                            ['model_type' => 'salla', 'model_id' => $response->data->id, 'product_id' => $row->id, 'dropshipper_id' => $this->dropshipper_id, 'move' => 1]
                        );
                        DB::table('dropshipper_mapping_products_skus')->where('model_type', 'salla')->where('product_id', $row->id)->where('dropshipper_id', $this->dropshipper_id)->delete();



                        if ($salla_product->skus) {
                            $product = $row;
                            $variants = [];
                            $k = 0;
                            foreach ($product->productVariants as $value) {
                                $variants[$k]['id'] = $value->id;
                                $variants[$k]['price'] = $value->price;
                                $variants[$k]['quantity']  = $value->quantity;
                                $variants[$k]['sku']  = $value->sku;
                                $y = 0;
                                foreach ($value->productVariantValue as $row) {
                                    $variants[$k]['variants_values'][$y]['id'] = $row->id;
                                    $variants[$k]['variants_values'][$y]['option_name'] = $row->attributeOption->name;
                                    $y++;
                                }

                                $k++;
                            }
                            $product->variants = $variants;
                            $this->mapingVarartion($salla_product, $product);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::channel('salla')->info($e->getMessage());
                continue;
            }
        }
    }


    public function mapingVarartion($salla_product, $product)
    {
        if ($salla_product->skus) {

            foreach ($salla_product->options as $row2) {
                DB::table('dropshipper_mapping_products_options')->insert(
                    [
                        'model_type' => 'salla',
                        'model_id' => $salla_product->id,
                        'product_id' => $product->id,
                        'dropshipper_id' => $this->dropshipper_id,
                        'option' => $row2->id,
                        'option_code' => $row2->name
                    ]
                );
            }
            foreach ($salla_product->skus as $row) {
                $this->getVarationId($product, $row, $salla_product);
            }
        }
    }


    public function getVarationId($product, $row, $salla_product)
    {
        $varationnamevalue = [];
        foreach ($row->related_option_values as $item) {
            foreach ($salla_product->options as $row2) {
                foreach ($row2->values as $value) {
                    if ($value->id == $item) {
                        array_push($varationnamevalue, $value->name);
                    }
                }
            }
        }

        foreach ($product->variants as $rowvar) {
            $countvaration = count($rowvar['variants_values']);
            if ($countvaration == 1) {
                if (in_array($rowvar['variants_values'][0]['option_name'], $varationnamevalue)) {

                    DB::table('dropshipper_mapping_products_skus')->insert(
                        [
                            'model_type' => 'salla',
                            'model_id' => $salla_product->id,
                            'product_id' => $product->id,
                            'dropshipper_id' => $this->dropshipper_id,
                            'varation' => $row->id,
                            'varation_id' => $rowvar['id'],
                            'sku' => $rowvar['sku']
                        ]
                    );
                    $data['sku'] = $rowvar['sku'];
                    $data['price'] = $rowvar['price'];
                    $data['stock_quantity'] = $rowvar['quantity'];
                    $this->addvarationsku($data, $row->id);
                }
            }
            if ($countvaration == 2) {
                if (
                    in_array($rowvar['variants_values'][0]['option_name'], $varationnamevalue)
                    && in_array($rowvar['variants_values'][1]['option_name'], $varationnamevalue)
                ) {
                    DB::table('dropshipper_mapping_products_skus')->insert(
                        [
                            'model_type' => 'salla',
                            'model_id' => $salla_product->id,
                            'product_id' => $product->id,
                            'dropshipper_id' => $this->dropshipper_id,
                            'varation' => $row->id,
                            'varation_id' => $rowvar['id'],
                            'sku' => $rowvar['sku']
                        ]
                    );

                    $data['sku'] = $rowvar['sku'];
                    $data['price'] = $rowvar['price'];
                    $data['stock_quantity'] = $rowvar['quantity'];
                    $this->addvarationsku($data, $row->id);
                }
            }
            if ($countvaration == 3) {
                if (
                    in_array($rowvar['variants_values'][0]['option_name'], $varationnamevalue)
                    && in_array($rowvar['variants_values'][1]['option_name'], $varationnamevalue)
                    && in_array($rowvar['variants_values'][2]['option_name'], $varationnamevalue)
                ) {
                    DB::table('dropshipper_mapping_products_skus')->insert(
                        [
                            'model_type' => 'salla',
                            'model_id' => $salla_product->id,
                            'product_id' => $product->id,
                            'dropshipper_id' => $this->dropshipper_id,
                            'varation' => $row->id,
                            'varation_id' => $rowvar['id'],
                            'sku' => $rowvar['sku']
                        ]
                    );

                    $data['sku'] = $rowvar['sku'];
                    $data['price'] = $rowvar['price'];
                    $data['stock_quantity'] = $rowvar['quantity'];
                    $this->addvarationsku($data, $row->id);
                }
            }
            if ($countvaration == 4) {
                if (
                    in_array($rowvar['variants_values'][0]['option_name'], $varationnamevalue)
                    && in_array($rowvar['variants_values'][1]['option_name'], $varationnamevalue)
                    && in_array($rowvar['variants_values'][2]['option_name'], $varationnamevalue)
                    && in_array($rowvar['variants_values'][3]['option_name'], $varationnamevalue)
                ) {
                    DB::table('dropshipper_mapping_products_skus')->insert(
                        [
                            'model_type' => 'salla',
                            'model_id' => $salla_product->id,
                            'product_id' => $product->id,
                            'dropshipper_id' => $this->dropshipper_id,
                            'varation' => $row->id,
                            'varation_id' => $rowvar['id'],
                            'sku' => $rowvar['sku']
                        ]
                    );

                    $data['sku'] = $rowvar['sku'];
                    $data['price'] = $rowvar['price'];
                    $data['stock_quantity'] = $rowvar['quantity'];
                    $this->addvarationsku($data, $row->id);
                }
            }
        }
    }

    public function addvarationsku($varation, $varation_id)
    {

        try {
            $client = new \GuzzleHttp\Client();
            if ($varation) {

                $apiRequest = $client->request('put', 'https://api.salla.dev/admin/v2/products/variants/' . $varation_id, [
                    'body' => json_encode($varation),
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => $this->token,
                        'Content-Type' => 'application/json',
                    ],
                ]);
                $response = json_decode($apiRequest->getBody());

                return true;
                //   return $this->apiResponse([],"The data has been sent to salla");

            }
        } catch (IdentityProviderException $e) {
            // Failed to get the access token or merchant details.
            // show an error message to the merchant with good UI
            return $this->apiResponse(
                $data = [],
                $message = "error",
                $code = 400,
                $e->getMessage()
            );
        }
    }
    private function getProductData($product, $optionsOldrop)
    {

        $i = 0;
        $myImages = [];
        $options = [];
        foreach ($product->logo as $row) {
            $myImages[$i]['original'] = $this->getLogo($row)['file'];
            $myImages[$i]['thumbnail'] = $this->getLogo($row)['file'];
            $myImages[$i]['alt'] = 'image';
            $myImages[$i]['default'] = true;

            $i++;
        }
        $x = 0;
        foreach ($optionsOldrop as $kv => $value) {

            $values = [];
            $k = 0;
            foreach ($value as $row) {

                $values[$k]['name']  = $row;
                $k++;
            }
            $options[$x]['values'] = $values;
            $options[$x]['name'] = $kv;
            $options[$x]['display_type'] = 'text';
            $x++;
        }


        return [
            'name' => $product->name->value ?? "",
            'price' => $product->cost_price,
            'sale_price' => $product->cost_price,
            'cost_price' => $product->cost_price,
            'status' => "out",
            'product_type' => "product",
            'quantity' => $product->quantity,
            'description' => $product->description->value ?? "",
            'sku' => $product->sku,
            'weight' => $product->weight,
            'images' => $myImages,
            "options" => $options,
        ];
    }

    private function getLogo($logo)
    {
        if (in_array($logo->type, [mediaType()['dm']])) {
            $path = pathType()['up'];
        } elseif (in_array($logo->type, [mediaType()['am'], mediaType()['lm']])) {
            $path = pathType()['ip'];
        } else {
            $path = pathType()['ip'];
        }
        return [
            'id' => $logo->id,
            'file' => getFile($logo->file, $path, getFileNameServer($logo)) ??  asset('dashboard') . '/assets/media/svg/avatars/blank.svg',
        ];
    }

    public function deleteOption($product)
    {
        $client = new \GuzzleHttp\Client();
        $options = DB::table('dropshipper_mapping_products_options')->where('model_type', 'salla')->where('product_id', $product->id)
            ->where('dropshipper_id', $this->dropshipper_id)
            ->get();
        foreach ($options as $row) {
            $apiRequest = $client->request("delete", 'https://api.salla.dev/admin/v2/products/options/' . $row->option, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $this->token,
                    'Content-Type' => 'application/json',
                ],
            ]);
            $response = json_decode($apiRequest->getBody());
        }

        DB::table('dropshipper_mapping_products_options')->where('model_type', 'salla')->where('product_id', $product->id)
            ->where('dropshipper_id', $this->dropshipper_id)
            ->delete();
    }
    public function addOption($product, $optionsOldrop, $productmaping)
    {
        $client = new \GuzzleHttp\Client();
        foreach ($optionsOldrop as $kv => $value) {

            $values = [];
            $k = 0;
            foreach ($value as $row) {

                $values[$k]['name']  = $row;
                $k++;
            }
            $options['values'] = $values;
            $options['name'] = $kv;
            $options['display_type'] = 'text';

            $apiRequest = $client->request('POST', 'https://api.salla.dev/admin/v2/products/' . $productmaping->model_id . '/options', [
                'body' => json_encode($options),
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $this->token,
                    'Content-Type' => 'application/json',
                ],
            ]);
            $response = json_decode($apiRequest->getBody());
            $option = $response->data;
        }
    }
}
