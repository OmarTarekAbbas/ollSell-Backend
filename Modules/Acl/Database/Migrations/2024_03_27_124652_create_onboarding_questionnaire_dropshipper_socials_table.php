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
        Schema::create('onboarding_questionnaire_dropshipper_socials', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dropshipper_id');
            $table->foreign('dropshipper_id','onboarding_questionnaire_dropshipper_socials')->references('id')->on('dropshippers')->onDelete('cascade');
            $table->string('social', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('onboarding_questionnaire_dropshipper_socials');
    }
};
