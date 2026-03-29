<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixOrderChargeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
        // We use change() to convert it to a decimal
        // 15,4 allows for tiny SMM prices like 0.0025
        $table->decimal('charge', 15, 8)->default(0.0000)->change();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('orders', function (Blueprint $table) {
        $table->string('charge')->nullable()->change();
       });
    }
}
