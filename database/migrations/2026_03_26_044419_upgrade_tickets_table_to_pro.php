<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpgradeTicketsTableToPro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
           if (!Schema::hasColumn('tickets', 'category_id')) {
                $table->foreignId('category_id')
                      ->nullable()
                      ->after('user_id')
                      ->constrained('ticket_categories')
                      ->onDelete('set null');
            }

            // 2. Add Priority for better admin management
            if (!Schema::hasColumn('tickets', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high'])
                      ->default('medium')
                      ->after('status');
            }

            // 3. Modernize the Status column
            // We change it to a string/enum for better readability in code
            $table->string('status')->default('pending')->change();

            // 4. Add a timestamp for the last activity
            if (!Schema::hasColumn('tickets', 'last_reply_at')) {
                $table->timestamp('last_reply_at')->nullable()->after('updated_at');
            }

            // 5. Indexing for high-speed SMM panel performance
            $table->index('status');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'priority', 'last_reply_at']);
            // Note: Reverting a 'change()' on status usually requires manual SQL or doctrine/dbal
        });
    }
}
