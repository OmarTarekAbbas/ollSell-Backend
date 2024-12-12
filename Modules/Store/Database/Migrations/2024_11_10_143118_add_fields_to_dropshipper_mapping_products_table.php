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
        Schema::table('dropshipper_mapping_products', function (Blueprint $table) {
            $table->decimal('selling_price', 10, 2)->nullable()->after('move');
        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
        Schema::table('dropshipper_mapping_products', function (Blueprint $table) {
            $table->dropColumn('selling_price');
        });
    }
};
