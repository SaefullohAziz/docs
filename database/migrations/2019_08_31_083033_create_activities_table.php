<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->index();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade')->onUpdate('cascade');
            $table->string('type');
            $table->date('date');
            $table->date('until_date')->nullable();
            $table->time('time')->nullable();
            $table->string('destination')->nullable();
            $table->string('participant')->nullable();
            $table->string('amount_of_teacher')->nullable();
            $table->string('amount_of_acp_student')->nullable();
            $table->string('amount_of_reguler_student')->nullable();
            $table->string('amount_of_student')->nullable();
            $table->string('implementer');
            $table->string('activity')->nullable();
            $table->string('activity_time')->nullable();
            $table->string('period')->nullable();
            $table->string('submission_letter')->nullable();
            $table->text('detail')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
