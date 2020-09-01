<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carriers', function (Blueprint $table) {

            $table->id();
            $table->string('c_name');
            $table->string('c_regNumber');
            $table->bigInteger('c_capacity');
            $table->unsignedBigInteger('c_owner');
            $table->timestamps();


            $table->foreign('c_owner')->references('id')->on('train_service_providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carriers');
    }
}
