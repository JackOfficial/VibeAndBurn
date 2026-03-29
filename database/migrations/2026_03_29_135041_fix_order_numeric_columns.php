<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixOrderNumericColumns extends Migration
{
    public function up()
{
    // 1. Force all empty strings or nulls to '0' so the conversion can happen
    DB::statement("UPDATE orders SET quantity = '0' WHERE quantity = '' OR quantity IS NULL");
    DB::statement("UPDATE orders SET start_count = '0' WHERE start_count = '' OR start_count IS NULL");
    DB::statement("UPDATE orders SET remains = '0' WHERE remains = '' OR remains IS NULL");

    // 2. Remove any accidental non-numeric characters (like spaces or dots)
    DB::statement("UPDATE orders SET quantity = REPLACE(quantity, ' ', '')");
    DB::statement("UPDATE orders SET start_count = REPLACE(start_count, ' ', '')");
    DB::statement("UPDATE orders SET remains = REPLACE(remains, ' ', '')");

    // 3. Now run the type change
    Schema::table('orders', function (Blueprint $table) {
        $table->integer('quantity')->default(0)->change();
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