<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrantPicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grant_pics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('grant_id')->index();
            $table->foreign('grant_id')->references('id')->on('grants')->onDelete('cascade')->onUpdate('cascade');
            $table->uuid('pic_id')->index();
            $table->foreign('pic_id')->references('id')->on('pics')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('grant_pics');
    }
}
