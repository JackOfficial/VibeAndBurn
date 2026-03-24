<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetservicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('getservices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('service');
            $table->string('name');
            $table->string('type');
            $table->string('category');
            $table->string('rate');
            $table->string('min');
            $table->string('max');
            $table->string('refill');
            $table->string('cancel');
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
        Schema::dropIfExists('getservices');
    }
}
