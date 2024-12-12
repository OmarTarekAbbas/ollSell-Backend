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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customerName', 255)->index()->change();
            $table->string('customerPhone', 255)->index()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customerName', 255)->change();
            $table->string('customerPhone', 255)->change();
        });
    }
};
