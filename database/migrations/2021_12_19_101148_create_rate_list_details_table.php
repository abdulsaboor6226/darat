<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateListDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_list_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rate_list_id');
            $table->string('name');
            $table->string('size');
            $table->string('size_name');
            $table->string('min_price');
            $table->string('max_price');
            $table->timestamps();
            $table->foreign('rate_list_id')->references('id')->on('rate_lists')->onDelete('cascade');
            $table->index(['rate_list_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rate_list_details');
    }
}
