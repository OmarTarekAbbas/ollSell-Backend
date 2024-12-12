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
        Schema::create('attempts_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('status_id');
            $table->integer('sub_status_id')->nullable();
            $table->integer('remark_id')->nullable();
            $table->integer('attempts_count')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('first_validation')->nullable();
            $table->timestamp('last_edit_order')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down() {}
};
