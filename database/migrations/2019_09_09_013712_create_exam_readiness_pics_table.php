<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamReadinessPicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_readiness_pics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_readiness_id')->index();
            $table->foreign('exam_readiness_id')->references('id')->on('exam_readinesses')->onDelete('cascade')->onUpdate('cascade');
            $table->uuid('pic_id')->index();
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
        Schema::dropIfExists('exam_readiness_pics');
    }
}
