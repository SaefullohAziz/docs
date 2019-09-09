<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamReadinessStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_readiness_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('exam_readiness_id')->index();
            $table->foreign('exam_readiness_id')->references('id')->on('exam_readinesses')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('status_id')->index();
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('log_id')->index();
            $table->foreign('log_id')->references('id')->on('activity_logs')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('exam_readiness_statuses');
    }
}
