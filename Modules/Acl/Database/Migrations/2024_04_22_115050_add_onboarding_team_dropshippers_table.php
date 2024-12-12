<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dropshippers', function (Blueprint $table) {
            $table->unsignedInteger('onboarding_user_id')->nullable()->default(0);
            $table->Integer('finish_onboarding')->nullable()->default(0);
            $table->unsignedInteger('account_manger_user_id')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dropshippers', function (Blueprint $table) {
            $table->dropColumn(['onboarding_user_id','finish_onboarding','account_manger_user_id']);
        });
    }
};
