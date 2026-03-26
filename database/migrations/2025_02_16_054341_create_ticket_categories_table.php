<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->string('name')->after('id'); // e.g. "Payment", "Instagram", "TikTok"
            $table->string('slug')->unique()->after('name'); // e.g. "payment-issues"
            $table->boolean('is_active')->default(true)->after('slug'); // To toggle visibility
            $table->integer('sort_order')->default(0)->after('is_active'); // To control which shows first in the dropdown
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
        Schema::dropIfExists('ticket_categories');
    }
}