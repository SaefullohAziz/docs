<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolStatusUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_status_updates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->index();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('school_status_id')->index();
            $table->foreign('school_status_id')->references('id')->on('school_statuses')->onDelete('cascade')->onUpdate('cascade');
            $table->string('participant')->nullable();
            $table->string('total')->nullable();
            $table->date('date')->nullable();
            $table->string('location')->nullable();
            $table->text('detail')->nullable();
            $table->enum('email_status', ['1', '0'])->nullable();
            $table->string('created_by');
            $table->unsignedBigInteger('staff_id')->nullable()->index();
            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('set null')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('school_status_updates');
    }
}
