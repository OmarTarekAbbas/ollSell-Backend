<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddMegaColumnToDropshippersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * return void
     */
    public function up()
    {
        Schema::table('dropshippers', function ($table) {
            $table->boolean('mega')->default(false);
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
