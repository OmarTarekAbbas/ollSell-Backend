<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
//todo change
class UniqueSku implements ValidationRule
{
    protected $id;
    public $skus = array();

    /**
     * Run the validation rule.
     *
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!empty(request("sku")))
        {
        array_push($this->skus, request("sku"));
        if(!empty(request("has_variants")) && !empty(request("variants")))
        {
            foreach(request("variants") as $x => $y)
            {
                array_push($this->skus, request("variants")[$x]["sku"]);
                $check = $this->CheckInDataBase(request("variants")[$x]["sku"],$this->id);
                if($check['status'] == false)
                {
                    $fail($check['message']);
                }
            }
            if($this->CheckInRequest())
            {
                $fail('This SKU is already duplication in request.');
            }
        }
        $check = $this->CheckInDataBase(request("sku"),$this->id);
        if($check['status'] == false)
        {
            $fail($check['message']);
        }
        }
    }

    public function CheckInRequest()
    {
        if(count(array_unique($this->skus)) != count($this->skus))
        {
            return true;
        }else
        {
            return false;
        }
    }

    public function CheckInDataBase($sku, $id = null)
    {
        $product_variants = DB::table('product_variants')->where('sku', $sku);
        if($id)
        {
            $product_variants = $product_variants->where('product_id', "!=", $id);
        }
        $product_variants = $product_variants->count();
        if($product_variants)
        {
            return ['status' => false, 'message' => 'This SKU is already taken by variant.'];
        }
        $product_variants = DB::table('products')->where('sku', $sku);
        if($id)
        {
            $product_variants = $product_variants->where('id', "!=", $id);
        }
        $product_variants = $product_variants->count();
        if($product_variants)
        {
            return ['status' => false, 'message' => 'This SKU is already taken by product.'];
        }
        $product_variants = DB::table('bundles')->where('sku', $sku);
        if($id)
        {
            $product_variants = $product_variants->where('id', "!=", $id);
        }
        $product_variants = $product_variants->count();
        if($product_variants)
        {
            return ['status' => false, 'message' => 'This SKU is already taken by Bundle.'];
        }
        return ['status' => true];
    }
}
