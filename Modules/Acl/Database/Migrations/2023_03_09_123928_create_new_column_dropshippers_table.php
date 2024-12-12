<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNewColumnDropshippersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::table('dropshippers', function ($table) {
            $table->float('profit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * return void
     */
    public function down()
    {
    }
}
