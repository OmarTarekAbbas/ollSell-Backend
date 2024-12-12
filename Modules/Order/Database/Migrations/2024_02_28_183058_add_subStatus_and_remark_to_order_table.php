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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_status_id')->nullable();
            $table->unsignedBigInteger('remark_id')->nullable();

            $table->foreign('sub_status_id')->references('id')->on('sub_statuses')->onDelete('set null');
            $table->foreign('remark_id')->references('id')->on('remarks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('sub_status_id');
            $table->dropColumn('remark_id');
        });
    }
};
