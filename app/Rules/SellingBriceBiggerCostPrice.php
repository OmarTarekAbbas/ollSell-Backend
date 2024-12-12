<?php

namespace App\Rules;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

//todo change
class SellingBriceBiggerCostPrice implements ValidationRule
{
    protected $product_id;
    protected $selling_price;
    protected $bundle_id;

    public function __construct($bundle_id, $product_id ,$selling_price,)
    {
        $this->product_id = $product_id;
        $this->selling_price = $selling_price;
        $this->bundle_id = $bundle_id;
    }

    /**
     * The function checks if the value of Price After Discount is less than the value of Original
     * Price.
     * 
     * param attribute The attribute parameter represents the name of the attribute being validated.
     * In this case, it could be "Price After Discount".
     * param value The value parameter represents the value of the attribute being validated. In this
     * case, it is the value of the "Price After Discount" attribute.
     * 
     * @return a boolean value indicating whether the value of the attribute is less than the original
     * price.
     */

     public function validate(string $attribute, mixed $value, Closure $fail): void
     { 
        if($this->product_id){
            $product=DB::table('products')->find($this->product_id);
            if($product->cost_price >= $this->selling_price)
            {
                $fail('Selling Brice must be Bigger Cost Price.');      
            }
        }
        else if($this->bundle_id){
            $bundle=DB::table('bundles')->find($this->bundle_id);
            if($bundle->cost_price > $this->selling_price)
            {
                $fail("Selling Brice must be bigger than bundle's Cost price.");
            }
        }
     }

}