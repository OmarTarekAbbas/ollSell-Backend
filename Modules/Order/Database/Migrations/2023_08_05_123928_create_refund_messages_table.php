<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::create('refund_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message')->nullable();
         
            $table->unsignedInteger('order_refund_id');
            $table->foreign('order_refund_id')->references('id')->on('order_refunds')->onDelete('cascade');
            
            $table->morphs('messagable');
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
        Schema::dropIfExists('refund_messages');
    }
}
