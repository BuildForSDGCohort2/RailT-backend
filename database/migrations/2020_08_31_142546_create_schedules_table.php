<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->timestamp('departure_time');
            $table->timestamp('arriva_time')->nullable();
            $table->unsignedBigInteger('from');
            $table->unsignedBigInteger('to');
            $table->unsignedBigInteger('carrier');
            $table->timestamps();

            $table->foreign('from')->references('id')->on('stations')->onDelete('cascade');
            $table->foreign('to')->references('id')->on('stations')->onDelete('cascade');
            $table->foreign('carrier')->references('id')->on('carriers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
