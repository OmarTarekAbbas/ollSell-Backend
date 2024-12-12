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
        Schema::table('dropshippers', function (Blueprint $table) {
            $table->string('onboarding_questionnaire_number', 255)->default(1)->nullable();
            $table->string('is_old_dropshipper', 255)->nullable();
            $table->string('number_years_dropshipper', 255)->nullable();
            $table->string('cost_month_dropshipper', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dropshippers', function (Blueprint $table) {
            $table->dropColumn('onboarding_questionnaire_number');
            $table->dropColumn('is_old_dropshipper');
            $table->dropColumn('number_years_dropshipper');
            $table->dropColumn('cost_month_dropshipper');
        });
    }
};
