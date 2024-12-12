<?php

namespace Modules\MasterCatalog\Actions\Product;

use Modules\MasterCatalog\Entities\Product;
use Modules\CoreData\Entities\Category;

class UpdateCommissionProductAction
{
    protected $oldCommission;
    protected $category;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct($oldCommission, $category)
    {
        $this->oldCommission = $oldCommission;
        $this->category = $category;
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
    {//todo change
        $products = Product::select('products.*')->join('category_product', 'products.id', '=', 'category_product.product_id')->where('category_product.category_id', $this->category->id)->where('products.custam_commission', 0)->get();

        foreach ($products as $row) {

            $totalCommission = Category::join('category_product', 'categories.id', '=', 'category_product.category_id')->where('category_product.product_id', $row->id)->max('categories.commission');
            if ($totalCommission <= $this->category->commission) {

                $row->commission = round(($row->supplier_price_cost * $totalCommission / 100), 2);
                $row->vat_commission =  round(($row->commission * setting('shipping_fee')), 2);
                $row->cost_price = $row->supplier_price_cost + $row->commission + $row->vat_commission + $row->supplier_price_vat;
                $row->save();
            }
        }
    }
}
