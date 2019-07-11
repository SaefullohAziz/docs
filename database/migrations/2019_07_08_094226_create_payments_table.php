<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->index();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade')->onUpdate('cascade');
            $table->string('type');
            $table->string('invoice')->nullable();
            $table->date('date');
            $table->decimal('total', 13, 4);
            $table->string('method');
            $table->string('payment_receipt');
            $table->string('bank_sender')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bill_number')->nullable();
            $table->string('on_behalf_of')->nullable();
            $table->string('receiver_bank_name')->nullable();
            $table->string('receiver_bill_number')->nullable();
            $table->string('receiver_on_behalf_of')->nullable();
            $table->string('commitment_letter')->nullable();
            $table->string('bank_account_book')->nullable();
            $table->string('npwp_number')->nullable();
            $table->string('npwp_on_behalf_of')->nullable();
            $table->text('npwp_address')->nullable();
            $table->string('npwp_file')->nullable();
            $table->string('notif')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
