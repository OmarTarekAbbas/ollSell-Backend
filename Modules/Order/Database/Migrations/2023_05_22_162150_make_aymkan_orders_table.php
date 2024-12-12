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
            $table->integer('weight')->nullable();
            $table->string('tracking_number')->nullable();
            $table->longText('pdf_label')->nullable();
            $table->date('deliveryDate')->nullable();
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
        });
    }
};
