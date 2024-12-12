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
        Schema::table('dropshippers', function ($table) {
            $table->boolean('isVerified')->default(false);
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('business_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
};
