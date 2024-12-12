<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVatToProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->float('commission')->default(0);
            $table->float('cost_price')->default(0);
            $table->float('vat')->default(0);
            $table->float('commission_vat')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('commission');
            $table->dropColumn('cost_price');
            $table->dropColumn('vat');
            $table->dropColumn('commission_vat');
        });
    }
}
