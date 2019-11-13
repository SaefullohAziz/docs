<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubsidyStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subsidy_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('subsidy_id')->index();
            $table->foreign('subsidy_id')->references('id')->on('subsidies')->onDelete('cascade')->onUpdate('cascade');
            $table->uuid('status_id')->index();
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade')->onUpdate('cascade');
            $table->uuid('log_id')->index();
            $table->foreign('log_id')->references('id')->on('activity_logs')->onDelete('cascade')->onUpdate('cascade');
            $table->date('paid_at')->nullable();
            $table->string('invoice')->nullable();
            $table->string('starting_price')->nullable();
            $table->string('paid_installment')->nullable();
            $table->string('lack_of_price')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('subsidy_statuses');
    }
}
