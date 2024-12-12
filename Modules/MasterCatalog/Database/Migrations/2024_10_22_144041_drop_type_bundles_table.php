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
        Schema::table('bundles', function (Blueprint $table) {
            $table->dropColumn('discount_type');
            $table->dropColumn('number_uses');
            $table->dropColumn('number_uses_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bundles', function (Blueprint $table) {
            $table->tinyInteger('discount_type')->default(1);
            $table->integer('number_uses')->default(0);
            $table->integer('number_uses_user')->default(0);
        });
    }
};
