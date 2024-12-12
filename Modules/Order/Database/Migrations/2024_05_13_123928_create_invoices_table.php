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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedInteger('dropshipper_id');
            $table->foreign('dropshipper_id')->references('id')->on('dropshippers')->onDelete('cascade');
            $table->unsignedInteger('dropshipper_branch_id')->nullable();
            $table->string('paymentMethod', 255);
            $table->float('costPrice');
            $table->float('subTotal');
            $table->float('grandTotal');
            $table->float('totalVat');
            $table->float('net_profit');
            $table->longText('pdf_link')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
