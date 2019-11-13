<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamReadinessStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_readiness_students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_readiness_id')->index();
            $table->foreign('exam_readiness_id')->references('id')->on('exam_readinesses')->onDelete('cascade')->onUpdate('cascade');
            $table->uuid('student_id')->index();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('exam_readiness_students');
    }
}
