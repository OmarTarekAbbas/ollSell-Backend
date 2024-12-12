<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDropshippersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::create('dropshippers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('business_name', 255);
            $table->string('merchant_name', 255)->nullable();
            $table->string('email', 255)->unique()->index();
            $table->string('phone', 255)->unique()->index();
            $table->string('password');
            $table->boolean('email_verification')->default(0);
            $table->text('token')->nullable();
            $table->string('code_country')->nullable()->index();
            $table->string('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
        Schema::dropIfExists('dropshippers');
    }
}
