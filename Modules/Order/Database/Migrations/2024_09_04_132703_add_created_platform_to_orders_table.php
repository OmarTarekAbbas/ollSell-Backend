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
            $table->string('created_platform')->nullable();
        });
        $orders = \Illuminate\Support\Facades\DB::table('orders')->select('id','source_platform')->get();
        foreach($orders as $order)
        {
            \Illuminate\Support\Facades\DB::table('orders')
                ->where('id', $order->id)
                ->update(['created_platform'=>$order->source_platform]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'created_platform'
            ]);
        });
    }
};
