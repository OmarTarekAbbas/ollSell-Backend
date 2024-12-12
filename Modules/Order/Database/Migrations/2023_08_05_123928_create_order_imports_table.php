<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::create('order_imports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('paymentMethod', 255);
            $table->string('shippingMethod', 255);
            $table->integer('totalQuantity');
            $table->integer('countOrderItem');
            $table->float('subTotal');
            $table->float('shippingFees');
            $table->float('grandTotal');
            $table->string('customerName', 255);
            $table->string('customerPhone', 255);
            $table->string('customerAddress', 255);
            $table->text('customerLocation');
            $table->string('customerCity', 255);
            $table->text('customerLocation')->change()->nullable();
            $table->string('costPrice');

            $table->unsignedInteger('status_id');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->unsignedInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->unsignedInteger('dropshipper_id');
            $table->foreign('dropshipper_id')->references('id')->on('dropshippers')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('order_imports_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_import_id');
            $table->foreign('order_import_id')->references('id')->on('order_imports')->onDelete('cascade');
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('quantity');
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
        Schema::dropIfExists('order_imports_items');
        Schema::dropIfExists('order_imports');
    }
}
