<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_installments', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('payment_id')->index();
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade')->onUpdate('cascade');
            $table->date('date');
            $table->decimal('total', 13, 4);
            $table->string('method');
            $table->string('payment_receipt');
            $table->string('bank_sender')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bill_number')->nullable();
            $table->string('on_behalf_of')->nullable();
            $table->string('commitment_letter')->nullable();
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
        Schema::dropIfExists('payment_installments');
    }
}
