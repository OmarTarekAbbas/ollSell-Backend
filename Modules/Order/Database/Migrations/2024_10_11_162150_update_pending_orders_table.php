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
        Schema::table('pending_orders', function (Blueprint $table) {
            $table->longText('district')->nullable()->change();
            $table->longText('customer_city')->nullable()->change();
            $table->longText('customer_country')->nullable()->change();
            $table->longText('customer_address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
        Schema::table('pending_orders', function (Blueprint $table) {
            $table->string('district')->nullable()->change();
            $table->string('customer_country')->nullable()->change();
            $table->string('customer_city')->nullable()->change();
            $table->text('customer_address')->nullable()->change();
        });
    }
};
