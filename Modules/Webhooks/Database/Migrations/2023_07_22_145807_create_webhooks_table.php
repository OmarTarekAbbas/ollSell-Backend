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
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dropshipper_id');
            $table->string('event');
            $table->string('url');

            $table->foreign('dropshipper_id')->references('id')->on('dropshippers')->onDelete('cascade');
            $table->unique(['dropshipper_id', 'event']);
            
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
        Schema::dropIfExists('webhooks');
    }
};
