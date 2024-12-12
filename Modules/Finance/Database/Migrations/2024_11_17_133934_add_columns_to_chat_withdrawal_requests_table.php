<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chat_withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->text('message'); 
            $table->unsignedInteger('withdrawal_request_id'); 
            $table->morphs('messagable'); 
            $table->timestamps();

            $table->foreign('withdrawal_request_id')
                ->references('id')
                ->on('withdrawal_requests')
                ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down() {}
};
