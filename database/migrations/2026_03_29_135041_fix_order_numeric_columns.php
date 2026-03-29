<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixOrderNumericColumns extends Migration
{
   public function up()
{
    // 1. Specific Fix: Replace the letter 'O' with number '0'
    DB::statement("UPDATE orders SET quantity = '0' WHERE quantity = 'O'");
    DB::statement("UPDATE orders SET start_count = '0' WHERE start_count = 'O'");
    DB::statement("UPDATE orders SET remains = '0' WHERE remains = 'O'");

    // 2. Global Fix: If a value contains ANY non-numeric characters, force it to '0'
    // This handles 'O', 'N/A', ' ', or even '$' if any were missed
    DB::statement("UPDATE orders SET quantity = '0' WHERE quantity REGEXP '[^0-9.]'");
    DB::statement("UPDATE orders SET start_count = '0' WHERE start_count REGEXP '[^0-9.]'");
    DB::statement("UPDATE orders SET remains = '0' WHERE remains REGEXP '[^0-9.]'");

    // 3. Clean decimals (e.g., '0.00' to '0')
    DB::statement("UPDATE orders SET quantity = FLOOR(CAST(quantity AS DECIMAL(15,4))) WHERE quantity LIKE '%.%'");
    DB::statement("UPDATE orders SET start_count = FLOOR(CAST(start_count AS DECIMAL(20,4))) WHERE start_count LIKE '%.%'");
    DB::statement("UPDATE orders SET remains = FLOOR(CAST(remains AS DECIMAL(20,4))) WHERE remains LIKE '%.%'");

    // 4. Handle empty strings
    DB::statement("UPDATE orders SET quantity = '0' WHERE quantity = '' OR quantity IS NULL");
    DB::statement("UPDATE orders SET start_count = '0' WHERE start_count = '' OR start_count IS NULL");
    DB::statement("UPDATE orders SET remains = '0' WHERE remains = '' OR remains IS NULL");

    // 5. Run the type change
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