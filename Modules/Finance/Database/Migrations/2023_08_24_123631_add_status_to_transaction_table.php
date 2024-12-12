<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('isStatus')->default(0);
        });

        Schema::table('dropshippers', function (Blueprint $table) {
            $table->float('profitBalance')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('isStatus');
        });
        Schema::table('dropshippers', function (Blueprint $table) {
            $table->dropColumn('profitBalance');
        });
    }
}
