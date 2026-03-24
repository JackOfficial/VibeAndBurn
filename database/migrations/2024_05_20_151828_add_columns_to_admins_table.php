<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('isSuperAdmin')->default(false)->after('avatar');
            $table->boolean('view_permission')->default(true)->after('isSuperAdmin');
            $table->boolean('update_permission')->default(false)->after('view_permission');
            $table->boolean('delete_permission')->default(false)->after('update_permission');
            $table->boolean('edit_permission')->default(false)->after('delete_permission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('isSuperAdmin');
             $table->dropColumn('view_permission');
             $table->dropColumn('update_permission');
             $table->dropColumn('delete_permission');
             $table->dropColumn('edit_permission');
        });
    }
}
