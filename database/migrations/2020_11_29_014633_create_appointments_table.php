<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('gender');
            $table->string('birth');
            $table->string('address');
            $table->bigInteger('patient_id')->unsigned()->nullable();
            $table->bigInteger('doctor_id')->unsigned();
            $table->date('date');
            $table->time('time');
            $table->string('payment_method');
            $table->bigInteger('offer_id')->unsigned()->nullable();
            $table->bigInteger('tax_id')->unsigned()->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('patient_id')->references('id')->on('users');
            $table->foreign('doctor_id')->references('id')->on('users');
            // $table->foreign('offer_id')->references('id')->on('offers');
            // $table->foreign('tax_id')->references('id')->on('taxes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('appointments');
    }
}
