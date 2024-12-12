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
        Schema::table('dropshippers', function ($table) {
            $table->float('earningsWithdrawal')->nullable()->after('profitBalance');
        });

        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->float('total_amount_dropshipper')->nullable();
            $table->float('balance_dropshipper')->nullable();
            $table->float('withdraw_dropshipper')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dropshippers', function (Blueprint $table) {
            $table->dropColumn(['earningsWithdrawal']);
        });
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropColumn(['total_amount_dropshipper']);
            $table->dropColumn(['balance_dropshipper']);
            $table->dropColumn(['withdraw_dropshipper']);
        });
    }
};
