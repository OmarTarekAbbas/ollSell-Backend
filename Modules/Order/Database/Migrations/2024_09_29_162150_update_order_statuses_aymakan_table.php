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
        Schema::table('order_statuses_aymakan', function (Blueprint $table) {
            $table->string('reason_code', 255)->nullable();
            $table->string('reason_description', 255)->nullable();
            $table->timestamp('requested_delivery_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
        Schema::table('order_statuses_aymakan', function (Blueprint $table) {
            $table->string('reason_code', 255)->change();
            $table->string('reason_description', 255)->change();
            $table->timestamp('requested_delivery_date')->nullable();
        });
    }
};
