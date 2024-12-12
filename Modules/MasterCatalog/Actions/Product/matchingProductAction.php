<?php

namespace Modules\MasterCatalog\Actions\Product;

use Illuminate\Http\Request;
use Modules\MasterCatalog\Entities\Product;

class matchingProductAction
{
    protected $request;
    protected $product_id;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(Request $request, $id)
    {
        $this->request = $request;
        $this->product_id = $id;
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
    public function execute()
    {
        $product = Product::find($this->product_id);
        //todo change case
        if (!empty($this->request->thumbnail)) {
            return true;
        }
        if (!empty($this->request->logo)) {
            return true;
        }
        if (strcmp(trim($this->request->sku), trim($product->sku)) != 0) {

            return true;
        }
        if ($this->request->supplier_price_cost != $product->supplier_price_cost) {

            return true;
        }
        if ($this->request->warehouse_id != $product->warehouse_id) {

            return true;
        }

        if (strcmp(trim($this->request->name), $product->nameValue(2)) != 0) {

            return true;
        }
        if (strcmp(trim($this->request->description), $product->descriptionValue(2)) != 0) {

            return true;
        }
        $var = (!empty($this->request->variants)) ? $this->request->variants : json_decode($this->request->variants);
        if ($var == null) {
            $var = [];
        }
        if (count($var) != count(json_decode(@$product->variants_data))) {
          return true;
        } else {
            $i = 0;
            $varaents=json_decode(@$product->variants_data);
            foreach ($varaents as $row) {
                if (($this->request->variants[$i]['attribute_option_id'] != $row->attribute_option_id )
                || ($this->request->variants[$i]['attribute_id'] != $row->attribute_id )
                || ($this->request->variants[$i]['price'] != $row->price )
                || ($this->request->variants[$i]['sku'] != $row->sku )
                
                ) {
                 return true;
                }
                $i++;
            }
        }
    
     
        return false;
    }
}
