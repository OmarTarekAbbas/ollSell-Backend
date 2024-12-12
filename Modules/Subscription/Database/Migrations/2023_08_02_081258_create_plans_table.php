<?php

use Illuminate\Support\Facades\Schema;
use Modules\Subscription\Entities\Plan;
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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->decimal('monthly_price', 10, 2)->nullable();
            $table->decimal('monthly_price_after_discount', 10, 2)->nullable();
            $table->decimal('yearly_price', 10, 2)->nullable();
            $table->decimal('yearly_price_after_discount', 10, 2)->nullable();
            $table->boolean('free')->default(false);
            $table->boolean('status')->default(true);

            $table->timestamps();
        });

        $this->createNewPlan();

        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('feature_id');
            $table->timestamps();

            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->foreign('feature_id')->references('id')->on('features')->onDelete('cascade');
        });

        Schema::table('dropshippers', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable()->default(1);
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('set null');
            $table->dateTime('expirePlanAt', $precision = 0)->nullable();

        });

        Schema::create('dropshipper_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->unsignedInteger('dropshipper_id');
            $table->foreign('dropshipper_id')->references('id')->on('dropshippers')->onDelete('cascade');
            $table->string('type')->nullable();
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
        Schema::table('dropshippers', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['plan_id', 'expirePlanAt']);
        });

        Schema::dropIfExists('dropshipper_plans');
        Schema::dropIfExists('plan_features');
        Schema::dropIfExists('features');
        Schema::dropIfExists('plans');
    }

    private function createNewPlan()
    {
        $data = Plan::create([
            'free' => 1
        ]);

        foreach (language() as $lang) {
            if (isset($value['name'][$lang->code])) {
                $data->translation()->create(['key' => 'name', 'value' => $value['name'][$lang->code], 'language_id' => $lang->id]);
            }
            if (isset($value['description'][$lang->code])) {
                $data->translation()->create(['key' => 'description', 'value' => $value['description'][$lang->code], 'language_id' => $lang->id]);
            }
        }
    }
};
