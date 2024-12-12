<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
//todo change
class PriceAfterDiscountYoungerThanPrice implements Rule
{
    protected $originalPrice;

    public function __construct($originalPrice)
    {
        $this->originalPrice = $originalPrice;
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
    public function passes($attribute, $value)
    {
        // Ensure that Price After Discount is less than Original Price
        return floatval($value) < floatval($this->originalPrice);
    }

    /**
     * The function returns a message stating that the price after discount must be less than the cost
     * price.
     * 
     * @return the message "Price After Discount must be younger (less) than the Cost Price."
     */
    public function message()
    {
        return 'Price After Discount must be younger (less) than the Cost Price.';
    }
}