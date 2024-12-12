<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNewColumnWithdrawalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::table('withdrawal_requests', function ($table) {
            $table->string('bank_name', 255)->nullable();
            $table->string('bank_address', 255)->nullable();
            $table->string('swift_no', 255)->nullable();
            $table->string('beneficiary_name', 255)->nullable();
            $table->string('beneficiary_address', 255)->nullable();
            $table->string('beneficiary_mobile', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
    }
}
