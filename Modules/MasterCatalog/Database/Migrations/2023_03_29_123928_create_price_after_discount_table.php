<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; 

class CreatePriceAfterDiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up() 
    {
        Schema::table('products', function ($table) {
            $table->float('priceAfterDiscount')->default(0);
        });
    }
 
    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
    }
}
