<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubsidyPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subsidy_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('subsidy_id')->index();
            $table->foreign('subsidy_id')->references('id')->on('subsidies')->onDelete('cascade')->onUpdate('cascade');
            $table->uuid('payment_id')->index();
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('subsidy_payments');
    }
}
