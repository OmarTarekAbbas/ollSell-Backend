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
        Schema::create('dropshipper_segmentation', function (Blueprint $table) {
            $table->id();
            $table->integer('from');
            $table->integer('to');
            $table->timestamps();
        });

        $seeder = new \Database\Seeders\DropshipperSegmentationTableSeeder();
          $seeder->run();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dropshipper_segmentation');
    }
};
