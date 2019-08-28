<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSomeColumnOnPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->date('date')->nullable()->change();
            $table->decimal('total', 13, 4)->nullable()->change();
            $table->string('method')->nullable()->change();
            $table->string('payment_receipt')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->date('date')->nullable(false)->change();
            $table->decimal('total', 13, 4)->nullable(false)->change();
            $table->string('method')->nullable(false)->change();
            $table->string('payment_receipt')->nullable(false)->change();
        });
    }
}
