<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsIntoAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
            $table->string('submission_letter')->nullable()->after('id');
            $table->string('contact_person_phone_number')->nullable()->after('id');
            $table->string('contact_person')->nullable()->after('id');
            $table->string('arrival_point')->nullable()->after('id');
            $table->date('until_date')->nullable()->after('id');
            $table->date('date')->nullable()->after('id');
            $table->string('transportation')->nullable()->after('id');
            $table->string('participant')->nullable()->after('id');
            $table->string('number_of_participant')->nullable()->after('id');
            $table->string('destination')->nullable()->after('id');
            $table->string('type')->after('id');
            $table->uuid('school_id')->index()->after('id');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            Schema::enableForeignKeyConstraints();
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
            Schema::disableForeignKeyConstraints();
            $table->dropColumn(['type', 'destination', 'number_of_participant', 'participant', 'transportation', 'date', 'until_date', 'arrival_point', 'contact_person', 'contact_person_phone_number', 'submission_letter', 'deleted_at']);
        });
    }
}
