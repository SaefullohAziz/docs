<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('type');
            $table->string('name');
            $table->text('address');
            $table->string('province');
            $table->string('regency');
            $table->string('police_number');
            $table->string('since');
            $table->string('school_phone_number');
            $table->string('school_email');
            $table->string('school_web');
            $table->string('total_student');
            $table->string('acp_student');
            $table->string('department');
            $table->string('iso_certificate');
            $table->string('mikrotik_academy');
            $table->string('headmaster_name');
            $table->string('headmaster_phone_number');
            $table->string('headmaster_email');
            $table->string('proposal');
            $table->string('reference');
            $table->string('dealer_name');
            $table->string('dealer_phone_number');
            $table->string('dealer_email');
            $table->string('document');
            $table->string('notif')->nullable();
            $table->string('code');
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
        Schema::dropIfExists('schools');
    }
}
