<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubsidyPicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subsidy_pics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subsidy_id')->index();
            $table->foreign('subsidy_id')->references('id')->on('subsidies')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('pic_id')->index();
            $table->foreign('pic_id')->references('id')->on('pics')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('subsidy_pics');
    }
}
