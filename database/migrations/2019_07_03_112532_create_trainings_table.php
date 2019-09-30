<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('school_id')->index();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade')->onUpdate('cascade');
            $table->string('type');
            $table->enum('has_asset', ['2', '1'])->nullable();
            $table->date('date')->nullable();
            $table->date('until_date')->nullable();
            $table->string('implementation')->nullable();
            $table->string('approval_code')->nullable();
            $table->string('selection_result')->nullable();
            $table->string('room_type')->nullable();
            $table->string('room_size')->nullable();
            $table->string('booking_code');
            $table->string('batch')->nullable();
            $table->string('approval_letter_of_commitment_fee');
            $table->text('detail')->nullable();
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
        Schema::dropIfExists('trainings');
    }
}
