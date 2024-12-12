<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('paymentMethod', 255);
            $table->string('shippingMethod', 255);
            $table->integer('totalQuantity');
            $table->integer('countOrderItem');
            $table->float('subTotal');
            $table->float('shippingFees');
            $table->float('grandTotal');
            $table->unsignedInteger('dropshipper_id');
            $table->foreign('dropshipper_id')->references('id')->on('dropshippers')->onDelete('cascade');
            $table->string('status', 255);
            $table->string('customerName', 255);
            $table->string('customerPhone', 255);
            $table->string('customerAddress', 255);
            $table->text('customerLocation');
            $table->unsignedInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->string('customerCity', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
