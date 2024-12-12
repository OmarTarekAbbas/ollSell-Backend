<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::create('order_status_refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_refund_id');
            $table->foreign('order_refund_id')->references('id')->on('order_refunds')->onDelete('cascade');
            $table->unsignedInteger('status_id');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
            
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
    }
};
