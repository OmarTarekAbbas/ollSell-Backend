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
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedInteger('variant_id')->nullable();
            $table->string('sku')->nullable();
            $table->json('product_json')->nullable();
            $table->json('variants_json')->nullable();
            $table->dropColumn(['variants']);
            // $table->dropForeign(['product_id']);
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });
        
        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes(); // Add this line for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
