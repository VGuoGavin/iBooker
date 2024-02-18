<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signatures', function (Blueprint $table) {
            $table->string('booking_id');
            $table->bigInteger('signee_id')->unsigned();;
            $table->binary('signature');
            $table->timestamps();

            $table->primary(['booking_id', 'signee_id'])->unique();
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
        Schema::dropIfExists('signatures');
    }
}
