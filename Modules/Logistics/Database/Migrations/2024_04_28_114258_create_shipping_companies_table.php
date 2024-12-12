<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('phone', 255);
            $table->string('address', 255);
            $table->string('email', 255);
            $table->double('price', 8, 2);	
            $table->text('loading_unloading')->nullable();
            $table->text('grn')->nullable();
            $table->text('put_to_shelves')->nullable();
            $table->text('qc_process')->nullable();
            $table->timeTz('order_fulfillment_start_time',0);
            $table->timeTz('order_fulfillment_end_time', 0);
            $table->text('order_fulfillment')->nullable();
            $table->text('returns_management')->nullable();
            $table->text('inventory_management')->nullable();
            $table->text('vas_activity')->nullable();
            $table->integer('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_companies');
    }
};
