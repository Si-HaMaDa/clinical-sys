<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BranchDepart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('branch_department', function (Blueprint $table) {
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('department_id')->unsigned();
            $table->timestamps();
            $table->unique(['branch_id', 'department_id']);
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
            $table->foreign('department_id')->references('id')->on('departments')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('BranchDepart');
    }
}
