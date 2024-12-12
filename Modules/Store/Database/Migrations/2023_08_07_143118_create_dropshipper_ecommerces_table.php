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
        Schema::create('dropshipper_ecommerces', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dropshipper_id');
            $table->foreign('dropshipper_id')->references('id')->on('dropshippers')->onDelete('cascade');
            $table->bigInteger('owner_id');
            $table->bigInteger('store_id');
            $table->string('store_type');
            $table->string('phone');
            $table->string('username')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('avatar');
            $table->string('role');
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
        Schema::dropIfExists('dropshipper_ecommerces');
    }
};
