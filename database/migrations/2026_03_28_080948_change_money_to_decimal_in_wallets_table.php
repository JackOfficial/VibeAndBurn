<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMoneyToDecimalInWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('decimal_in_wallets', function (Blueprint $table) {
            $table->decimal('money', 19, 8)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('decimal_in_wallets', function (Blueprint $table) {
            $table->string('money')->default('0')->change();
        });
    }
}
