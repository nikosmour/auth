<?php

use App\Enum\MealPlanPeriodEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_owners', function (Blueprint $table) {
            $table->unsignedBigInteger('academic_id')->primary();
            $table->foreign('academic_id')->references('academic_id')->on('academics')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('money')->default(0);
            foreach (MealPlanPeriodEnum::names() as $period) {
                $table->unsignedInteger($period)->default(0);
            }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_owners');
    }
};
