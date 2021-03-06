<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeFieldTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('teaching_status')->default('no')->after('position');
            $table->string('date_of_birth')->after('gender');
            $table->string('address')->nullable()->after('position');
            $table->integer('nip')->length(20)->nullable()->after('school_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['teaching_status', 'date_of_birth', 'address', 'nip']);
        });
    }
}
