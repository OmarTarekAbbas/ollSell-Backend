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
        Schema::create('salla_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dropshipper_id');
            $table->foreign('dropshipper_id')->references('id')->on('dropshippers')->onDelete('cascade');

            $table->unsignedBigInteger('merchant_id'); // Unique Salla merchant ID
            $table->string('access_token');
            $table->string('refresh_token');
            $table->timestamp('expires_at');
            $table->string('store_name')->nullable(); // Store name for reference
            $table->string('store_domain')->nullable(); // Store domain for reference
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salla_tokens');
    }
};
