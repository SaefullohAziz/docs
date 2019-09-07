<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSomeColumnOnStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropForeign(['school_id']);
            $table->dropColumn(['school_id', 'generation', 'school_year', 'grade', 'department']);
            Schema::enableForeignKeyConstraints();
            $table->string('father_name')->nullable()->change();
            $table->string('father_education')->nullable()->change();
            $table->string('father_earning')->nullable()->change();
            $table->decimal('father_earning_nominal', 15, 2)->nullable()->change();
            $table->string('mother_education')->nullable()->change();
            $table->string('mother_earning')->nullable()->change();
            $table->decimal('mother_earning_nominal', 15, 2)->nullable()->change();
            $table->string('trustee_name')->nullable()->change();
            $table->string('trustee_education')->nullable()->change();
            $table->string('economy_status')->nullable()->change();
            $table->string('special_need')->nullable()->change();
            $table->string('mileage')->nullable()->change();
            $table->integer('distance')->nullable()->change();
            $table->string('diploma_number')->nullable()->change();
            $table->string('child_order')->nullable()->change();
            $table->string('sibling_number')->nullable()->change();
            $table->string('stepbrother_number')->nullable()->change();
            $table->string('step_sibling_number')->nullable()->change();
            $table->text('father_address')->nullable()->change();
            $table->text('trustee_address')->nullable()->change();
            $table->integer('computer_basic_score')->nullable()->change();
            $table->integer('intelligence_score')->nullable()->change();
            $table->integer('reasoning_score')->nullable()->change();
            $table->integer('analogy_score')->nullable()->change();
            $table->integer('numerical_score')->nullable()->change();
            $table->unsignedBigInteger('class_id')->index()->after('id');
            $table->foreign('class_id')->references('id')->on('student_classes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropForeign(['class_id']);
            $table->dropColumn(['class_id']);
            Schema::enableForeignKeyConstraints();
            $table->unsignedBigInteger('school_id')->index()->after('id');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade')->onUpdate('cascade');
            $table->string('grade')->after('gender');
            $table->string('school_year')->after('gender');
            $table->string('generation')->after('gender');
            $table->string('department')->after('nisn');
            $table->string('father_name')->nullable(false)->change();
            $table->string('father_education')->nullable(false)->change();
            $table->string('father_earning')->nullable(false)->change();
            $table->decimal('father_earning_nominal', 15, 2)->nullable(false)->change();
            $table->string('mother_education')->nullable(false)->change();
            $table->string('mother_earning')->nullable(false)->change();
            $table->decimal('mother_earning_nominal', 15, 2)->nullable(false)->change();
            $table->string('trustee_name')->nullable(false)->change();
            $table->string('trustee_education')->nullable(false)->change();
            $table->string('economy_status')->nullable(false)->change();
            $table->string('special_need')->nullable(false)->change();
            $table->string('mileage')->nullable(false)->change();
            $table->integer('distance')->nullable(false)->change();
            $table->string('diploma_number')->nullable(false)->change();
            $table->string('child_order')->nullable(false)->change();
            $table->string('sibling_number')->nullable(false)->change();
            $table->string('stepbrother_number')->nullable(false)->change();
            $table->string('step_sibling_number')->nullable(false)->change();
            $table->text('father_address')->nullable(false)->change();
            $table->text('trustee_address')->nullable(false)->change();
            $table->integer('computer_basic_score')->nullable(false)->change();
            $table->integer('intelligence_score')->nullable(false)->change();
            $table->integer('reasoning_score')->nullable(false)->change();
            $table->integer('analogy_score')->nullable(false)->change();
            $table->integer('numerical_score')->nullable(false)->change();
        });
    }
}
