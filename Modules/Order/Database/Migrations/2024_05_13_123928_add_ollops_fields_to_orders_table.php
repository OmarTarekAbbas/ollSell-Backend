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
            $table->string('ollops_token')->nullable();
            $table->string('ollops_order_id')->nullable();
            $table->timestamp('sent_to_ollops_at')->nullable();
            $table->string('ollops_confirmation_status')->nullable();
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
            $table->dropColumn('ollops_token');
            $table->dropColumn('ollops_order_id');
            $table->dropColumn('sent_to_ollops_at');
            $table->dropColumn('ollops_confirmation_status');
        });
    }
};
