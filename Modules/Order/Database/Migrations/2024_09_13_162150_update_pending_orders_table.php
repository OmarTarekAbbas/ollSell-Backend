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
            $table->text('source_platform')->nullable(); // Store location coordinates or similar data
            $table->dropColumn(['customer_location']);
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
            $table->text('customer_location')->nullable(); // Store location coordinates or similar data
            $table->dropColumn(['source_platform']);
        });
    }
};
