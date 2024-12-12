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
        Schema::table('dropshippers', function (Blueprint $table) {
            $table->string('bankAccountName', 255)->nullable();
            $table->string('bankName', 255)->nullable();
            $table->string('accountNumber', 255)->nullable();
            $table->string('iban', 255)->nullable();
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
};
