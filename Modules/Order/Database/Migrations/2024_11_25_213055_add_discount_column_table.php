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
        Schema::table('dropshippers', function (Blueprint $table) {
            $table->float('max_discount')->default(10);
        });

        Schema::create('discount_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->foreign('operator_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('discount_percent');
            $table->decimal('discount_value', 10, 2);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('allow_discounts')->default(false);
            $table->boolean('applied_discount')->default(false);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('discount')->default(0);
            $table->boolean('is_discount')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
};
