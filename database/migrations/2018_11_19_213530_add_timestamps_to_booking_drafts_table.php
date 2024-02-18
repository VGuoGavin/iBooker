<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampsToBookingDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_drafts', function (Blueprint $table) {
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_drafts', function (Blueprint $table) {
            $table->dropColumn('start_datetime');
            $table->dropColumn('end_datetime');
        });
    }
}
