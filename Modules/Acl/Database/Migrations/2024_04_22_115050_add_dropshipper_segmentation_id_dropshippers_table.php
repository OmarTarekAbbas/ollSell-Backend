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
            $table->unsignedBigInteger('dropshipper_segmentation_id')->default('1');
            $table->foreign('dropshipper_segmentation_id')->references('id')->on('dropshipper_segmentation')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dropshippers', function (Blueprint $table) {
            //
        });
    }
};
