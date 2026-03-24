<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->string('service');
            $table->string('rate_per_1000');
            $table->string('min_order');
            $table->string('max_order');
            $table->string('Average_completion_time');
            $table->string('quality');
            $table->string('start');
            $table->string('speed');
            $table->string('refill');
            $table->string('price_per_1000');
            $table->string('description');
            $table->integer('description')->default(1);
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
