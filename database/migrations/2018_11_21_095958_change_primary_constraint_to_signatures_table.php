<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePrimaryConstraintToSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signatures', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropForeign(['signee_id']);
            $table->dropPrimary();
            $table->primary(['booking_id', 'signee_id', 'message']);
            $table->foreign('booking_id')
                  ->references('id')->on('bookings')
                  ->onDelete('cascade');
            $table->foreign('signee_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signatures', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropForeign(['signee_id']);
            $table->dropPrimary();
            $table->primary(['booking_id', 'signee_id']);
            $table->foreign('booking_id')
                  ->references('id')->on('bookings')
                  ->onDelete('cascade');
            $table->foreign('signee_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }
}
