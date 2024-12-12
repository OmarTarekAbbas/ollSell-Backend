<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table('withdrawal_requests', function ($table) {
            $table->json('order_id')->nullable();
        });

        Schema::table('transactions', function ($table) {
            $table->integer('withdrawal_request_id')->nullable();
            $table->string('earning_type')->nullable();
            $table->dateTime('earning_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['order_id','earning_type','earning_date']);
        });
    }
};
