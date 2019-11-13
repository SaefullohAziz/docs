<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id')->index();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade')->onUpdate('cascade');
            $table->string('username')->nullable();
            $table->string('name');
            $table->string('nickname');
            $table->string('school_year');
            $table->string('province');
            $table->string('nisn');
            $table->string('department');
            $table->string('email');
            $table->string('gender');
            $table->string('grade');
            $table->string('generation');
            $table->string('father_name');
            $table->string('father_education');
            $table->string('father_earning');
            $table->decimal('father_earning_nominal', 15, 2);
            $table->string('mother_name');
            $table->string('mother_education');
            $table->string('mother_earning');
            $table->decimal('mother_earning_nominal', 15, 2);
            $table->string('trustee_name');
            $table->string('trustee_education');
            $table->string('economy_status');
            $table->string('religion');
            $table->string('blood_type');
            $table->string('special_need');
            $table->string('mileage');
            $table->integer('distance');
            $table->string('diploma_number');
            $table->integer('height');
            $table->integer('weight');
            $table->string('child_order');
            $table->string('sibling_number');
            $table->string('stepbrother_number');
            $table->string('step_sibling_number');
            $table->dateTime('dateofbirth');
            $table->text('address');
            $table->text('father_address');
            $table->text('trustee_address');
            $table->integer('phone_number');
            $table->string('photo')->default('default.png');
            $table->integer('computer_basic_score');
            $table->integer('intelligence_score');
            $table->integer('reasoning_score');
            $table->integer('analogy_score');
            $table->integer('numerical_score');
            $table->string('approval')->nullable();
            $table->string('notif')->nullable();
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
        Schema::dropIfExists('students');
    }
}
