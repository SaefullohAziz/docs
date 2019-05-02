<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSomeColumnsOnSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('dealer_name')->nullable()->change();
            $table->string('dealer_phone_number')->nullable()->change();
            $table->string('dealer_email')->nullable()->change();
            $table->string('document')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('dealer_name')->nullable(false)->change();
            $table->string('dealer_phone_number')->nullable(false)->change();
            $table->string('dealer_email')->nullable(false)->change();
            $table->string('document')->nullable(false)->change();
        });
    }
}
