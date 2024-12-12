<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::create('dropshipper_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dropshipper_id');
            $table->string('bank_name')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('swift_number')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('beneficiary_address')->nullable();
            $table->string('beneficiary_mobile')->nullable();
            $table->string('iban')->nullable();
            $table->string('currency')->nullable();
            $table->string('account_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->unsignedInteger('dropshipper_payment_id');
        });
        Artisan::call('dropshipper:dropshipper-payments');
        Schema::table('dropshippers', function (Blueprint $table) {
            $table->dropColumn('iban');
            $table->dropColumn('accountNumber');
            $table->dropColumn('bankName');
            $table->dropColumn('bankAccountName');
        });
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropColumn('account_number');
            $table->dropColumn('iban');
            $table->dropColumn('beneficiary_mobile');
            $table->dropColumn('beneficiary_address');
            $table->dropColumn('beneficiary_name');
            $table->dropColumn('swift_no');
            $table->dropColumn('bank_address');
            $table->dropColumn('bank_name');
        });
    }


    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
        Schema::dropIfExists('dropshipper_payments');
    }
};
