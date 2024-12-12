<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::create('bundles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku')->unique(); // Unique SKU
            $table->integer('quantity')->default(0);
            $table->integer('status')->default(1);
            $table->tinyInteger('discount_type')->default(1);
            $table->double('discount')->default(0);
            $table->double('total_price')->default(0);
            $table->dateTime('expire_date')->nullable();
            $table->integer('number_uses')->default(0);
            $table->integer('number_uses_user')->default(0);
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
        Schema::dropIfExists('bundles');
    }
}
