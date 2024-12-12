<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderRefundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::create('order_refunds', function (Blueprint $table) {
            $table->increments('id');
            // $table->unsignedInteger('order_item_id');
            // $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
            $table->unsignedInteger('status_id');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->integer('totalQuantity')->nullable();
            $table->integer('countOrderItem')->nullable();
            $table->float('grandTotal')->nullable();
            $table->text('reason')->nullable();
            $table->string('tracking_number')->nullable();
            $table->longText('pdf_label')->nullable();
            $table->date('deliveryDate')->nullable();
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
