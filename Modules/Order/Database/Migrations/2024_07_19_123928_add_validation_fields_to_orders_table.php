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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('validated_by')->nullable();
            $table->timestamp('first_message_time')->nullable();
            $table->timestamp('second_message_time')->nullable();
            $table->timestamp('third_message_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'validated_by',
                'first_message_time',
                'second_message_time',
                'third_message_time',
            ]);
        });
    }
};
