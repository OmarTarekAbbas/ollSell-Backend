<?php

namespace Modules\Store\Service;

use App\Mail\SendVerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Store\Repositories\DropshipperMappingProductRepository;
use Modules\Basic\Service\BasicService;
use Illuminate\Support\Facades\DB;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Modules\Store\Service\SallaAuthService;

class DropshipperMappingProductService extends BasicService
{
    protected $repo;
    protected $salla;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(DropshipperMappingProductRepository $repository, SallaAuthService $salaservice)
    {
        $this->repo = $repository;
        $this->salla = $salaservice;
    }

    public function getMappedProducts(Request $request)
    {
        $products = $this->repo->findBy($request);

        return $products;
    }


    public function storeMapping($salla_product, $product)
    {
        //todo change
        $request = new Request([
            'dropshipper_id' => Auth::guard('dropshipper')->user()->id,
            'model_type' => 'salla',
            'model_id' => $salla_product->id,
            'product_id' => $product->id,
            'move' => 1
        ]);
        $data = $this->repo->save($request);
        if ($salla_product->skus) {
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
        return true;
    }

    /**
     * It deletes the user's account
     *
     * param Request request The request object
     *
     * return The data is being returned.
     */
    public function destroy(Request $request, $id = null)
    {
        $data = $this->repo->delete(user()->id);
        if ($data) {
            return $data;
        }
        return false;
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
                        'dropshipper_id' => Auth::guard('dropshipper')->user()->id,
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

    public function deletedProduct(Request $request)
    {

        $dropshipper_id = Auth::guard('dropshipper')->user()->id;
        $this->salla->forUser(Auth::guard('dropshipper')->user());
        try {
            $this->salla->getNewAccessToken();
        } catch (IdentityProviderException $exception) {
            return $this->apiResponse(
                $data = [],
                $message = "error",
                $code = 400,
                $exception->getMessage()
            );
        }

        $product =  DB::table('dropshipper_mapping_products')->where('model_type', 'salla')->where('product_id',  $request->product_id)->where('dropshipper_id',  $dropshipper_id)->first();
        if (isset($product)) {
            $product_id_in_sall =  $product->model_id;
            try {
                $client = new \GuzzleHttp\Client();
                if ($product_id_in_sall) {

                    $apiRequest = $client->request('delete', 'https://api.salla.dev/admin/v2/products/' . $product_id_in_sall, [
                        'headers' => [
                            'Accept' => 'application/json',
                            'Authorization' => 'Bearer ' . $this->salla->token->access_token,
                            'Content-Type' => 'application/json',
                        ],
                    ]);
                    $response = json_decode($apiRequest->getBody());
                    if ($response->success == true) {
                        DB::table('dropshipper_mapping_products')->where('model_type', 'salla')->where('model_id',  $product_id_in_sall)->where('dropshipper_id',  $dropshipper_id)->delete();
                        DB::table('dropshipper_mapping_products_options')->where('model_type', 'salla')->where('model_id',  $product_id_in_sall)->where('dropshipper_id',  $dropshipper_id)->delete();
                        DB::table('dropshipper_mapping_products_options')->where('model_type', 'salla')->where('model_id',  $product_id_in_sall)->where('dropshipper_id',  $dropshipper_id)->delete();
                    }
                    return true;
                }
            } catch (IdentityProviderException $e) {
                return false;
                //     return $this->apiResponse(
                //     $data = [], $message = "error", $code = 400,$e->getMessage()
                // );
            }
        }

        return false;
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
                            'dropshipper_id' => Auth::guard('dropshipper')->user()->id,
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
                            'dropshipper_id' => Auth::guard('dropshipper')->user()->id,
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
                            'dropshipper_id' => Auth::guard('dropshipper')->user()->id,
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
                            'dropshipper_id' => Auth::guard('dropshipper')->user()->id,
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
        $this->salla->forUser(Auth::guard('dropshipper')->user());
        try {
            $client = new \GuzzleHttp\Client();
            if ($varation) {

                $apiRequest = $client->request('put', 'https://api.salla.dev/admin/v2/products/variants/' . $varation_id, [
                    'body' => json_encode($varation),
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->salla->token->access_token,
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
    public function update(Request $request, $id)
    {
        if ($request->amount) {
            $request->merge([
                'walletBalance' => user()->walletBalance - $request->amount,
            ]);
        }
        $data = $this->repo->save($request, user()->id);
        if ($data) {
            return new DropshipperResource($data);
        }
        return false;
    }

    public function updateSellingPrice(Request $request, $id)
    {
        $product = $this->repo->findOne($id);

        if($product->product->cost_price >= $request->selling_price) return $product;

        $product->update([
            'selling_price' => $request->selling_price
        ]);

        return $product->refresh();
    }
}
