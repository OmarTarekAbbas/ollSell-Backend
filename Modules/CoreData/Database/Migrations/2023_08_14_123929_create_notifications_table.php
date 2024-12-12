<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('content')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('user_type');
            $table->integer('seen')->nullable();
            $table->dateTime('seenAt', $precision = 0)->nullable();
            $table->timestamps();
        });

        Schema::table('dropshippers', function (Blueprint $table) {
            $table->integer('totalNotifications')->nullable();
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->integer('totalNotifications')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
