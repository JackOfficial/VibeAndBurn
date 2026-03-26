<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpgradeSupportsTableToThreaded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supports', function (Blueprint $table) {
            if (!Schema::hasColumn('supports', 'user_id')) {
                $table->foreignId('user_id')
                      ->nullable()
                      ->after('ticket_id')
                      ->constrained('users')
                      ->onDelete('cascade');
            }

            // 2. Add is_admin flag for UI bubble styling
            if (!Schema::hasColumn('supports', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('user_id');
            }

            // 3. Upgrade message from string (255 chars) to text (65,000+ chars)
            // This is safer for long SMM support logs
            $table->text('message')->nullable()->change();

            // 4. Drop the old 'reply' and 'status' columns 
            // because every reply is now just a new row in this table.
            if (Schema::hasColumn('supports', 'reply')) {
                $table->dropColumn('reply');
            }
            if (Schema::hasColumn('supports', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supports', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'is_admin']);
            $table->string('reply')->nullable();
            $table->string('status')->default(0);
        });
    }
}
