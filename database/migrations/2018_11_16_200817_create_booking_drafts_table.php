<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_drafts', function (Blueprint $table) {
            $table->string('id');
            $table->string('purpose')->nullable();
            $table->boolean('committed');
            $table->dateTime('committed_at')->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->bigInteger('booker_id')->unsigned();
            $table->foreign('booker_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->string('room_id');
            $table->foreign('room_id')
                  ->references('id')->on('rooms')
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
        Schema::dropIfExists('booking_drafts');
    }
}
