<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixOrderNumericColumns extends Migration
{
    public function up()
    {
        // Safety: Clean any accidental spaces or non-numeric characters first
        DB::statement("UPDATE orders SET quantity = REPLACE(quantity, ' ', '') WHERE quantity REGEXP '[^0-9]'");
        DB::statement("UPDATE orders SET start_count = REPLACE(start_count, ' ', '') WHERE start_count REGEXP '[^0-9]'");
        DB::statement("UPDATE orders SET remains = REPLACE(remains, ' ', '') WHERE remains REGEXP '[^0-9]'");

        Schema::table('orders', function (Blueprint $table) {
            // Quantity is usually a whole number
            $table->integer('quantity')->default(0)->change();

            // start_count and remains can be very large (e.g. 100M views)
            $table->bigInteger('start_count')->default(0)->change();
            $table->bigInteger('remains')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('quantity', 255)->nullable()->change();
            $table->string('start_count', 255)->nullable()->change();
            $table->string('remains', 255)->nullable()->change();
        });
    }
}