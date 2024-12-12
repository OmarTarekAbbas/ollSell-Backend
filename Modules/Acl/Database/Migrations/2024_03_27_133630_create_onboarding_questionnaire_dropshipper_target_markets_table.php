<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::create('onboarding_questionnaire_dropshipper_target_markets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dropshipper_id');
            $table->foreign('dropshipper_id','onboarding_questionnaire_dropshipper_id')->references('id')->on('dropshippers')->onDelete('cascade');
            $table->unsignedInteger('target_market_id');
            $table->foreign('target_market_id','onboarding_questionnaire_dropshipper_target_markets')->references('id')->on('target_markets')->onDelete('cascade');
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
        Schema::dropIfExists('onboarding_questionnaire_dropshipper_target_markets');
    }
};
