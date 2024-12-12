<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingOrdersItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::create('pending_orders_items', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('pending_order_id')
                ->constrained('pending_orders')
                ->onDelete('cascade'); // Foreign key referencing the pending_orders table
            $table->string('sku')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('selling_price', 8, 2)->nullable(); // Adjust decimal precision as needed
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
        Schema::dropIfExists('pending_orders');
    }
}