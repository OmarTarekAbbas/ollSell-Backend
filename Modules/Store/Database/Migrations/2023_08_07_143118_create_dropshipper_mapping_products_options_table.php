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
        Schema::create('dropshipper_mapping_products_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dropshipper_id');
            $table->foreign('dropshipper_id')->references('id')->on('dropshippers')->onDelete('cascade');
            $table->morphs('model');
            $table->bigInteger('product_id');
            $table->string('option');
            $table->string('option_code');
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
        Schema::dropIfExists('dropshipper_mapping_products_options');
    }
};
