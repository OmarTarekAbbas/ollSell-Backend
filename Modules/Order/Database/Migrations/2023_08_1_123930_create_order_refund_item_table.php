<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderRefundItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::create('order_refund_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_refund_id');
            $table->foreign('order_refund_id')->references('id')->on('order_refunds')->onDelete('cascade');

            $table->unsignedInteger('order_item_id');
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
            $table->integer('quantity')->nullable();
            $table->float('totalPrice')->nullable();
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
        Schema::dropIfExists('order_refunds');
    }
}
