<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches_services', function (Blueprint $table) {
            $table->id();
            $table->integer('branches_id')->unsigned();
            $table->integer('services_id')->unsigned();
            $table->foreign('branches_id')->references('id')->on('branches');
            $table->foreign('services_id')->references('id')->on('services');
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
        Schema::dropIfExists('branches_services');
    }
}
