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
            $table->string('source_platform', 255)->nullable()->index()->change();
            $table->string('created_platform', 255)->nullable()->index()->change();
            $table->string('paymentMethod', 255)->index()->change();
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
            $table->string('source_platform', 255)->nullable()->change();
            $table->string('source_platform', 255)->nullable()->change();
            $table->string('paymentMethod', 255)->nullable()->change();
        });
    }
};
