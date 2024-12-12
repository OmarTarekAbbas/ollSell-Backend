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
            $table->dropColumn(['merchant_name', 'email_verification']);
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
