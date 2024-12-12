<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::create('pending_orders', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->string('district')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('customer_country')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->unsignedInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->text('customer_location')->nullable(); // Store location coordinates or similar data
            $table->string('payment_method')->nullable(); // Adjust type if necessary (string or integer)
            $table->unsignedInteger('dropshipper_id');
            $table->foreign('dropshipper_id')->references('id')->on('dropshippers')->onDelete('cascade');
            $table->json('duplicated_order_ids')->nullable(); // JSON field for storing duplicated order IDs
            $table->boolean('is_duplicated')->default(false);
            $table->boolean('invalid')->default(false);
            $table->json('message')->nullable();
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