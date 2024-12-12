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
        Schema::table('order_items', function (Blueprint $table) {
            $table->float('total_profit')->nullable();
            $table->float('vat_profit')->nullable();
            $table->float('net_profit')->nullable();
            $table->float('product_vat')->nullable();

        });
        Schema::table('orders', function (Blueprint $table) {
            $table->float('net_profit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
