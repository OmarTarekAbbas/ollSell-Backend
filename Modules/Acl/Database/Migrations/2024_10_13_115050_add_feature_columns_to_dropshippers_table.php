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
            $table->boolean('extra_product_feature_enabled')->default(false);
            $table->decimal('product_price_percentage', 5, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dropshippers', function (Blueprint $table) {
            $table->dropColumn(['extra_product_feature_enabled', 'product_price_percentage']);
        });
    }
};
